<?php

namespace App\Repositories\Eloquent;

use App\Models\WalletHistory;
use App\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class WalletRepository implements WalletRepositoryInterface
{
    protected PayPalClient $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient();
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function addWallet(Request $request): JsonResponse
    {
        $request->validate([
            'wallet_amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:paypal,stripe,wallet_one',
        ]);
        
        $user = auth()->user();
        if (! $user) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
            ], 401);
        }
        $responseJson = [];
        $amount = $request->wallet_amount;
        $paymentType = ucfirst($request->payment_type);

        try {
            if ($paymentType === 'Paypal') {
                $this->provider->getAccessToken();

                $order = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'amount' => [
                                'currency_code' => 'USD',
                                'value' => $amount,
                            ],
                        ],
                    ],
                    'application_context' => [
                        'return_url' => url('user/paypal-payment-success-wallet'),
                        'cancel_url' => url('payment-failed'),
                    ],
                ];

                $response = $this->provider->createOrder($order);
                // Decode response if it's not an array
                if (is_string($response)) {
                    $response = json_decode($response, true);
                } elseif ($response instanceof \Psr\Http\Message\StreamInterface) {
                    $response = json_decode($response->getContents(), true);
                }

                if (! is_array($response) || ! isset($response['id'])) {
                    $responseJson = [
                        'code' => 500,
                        'message' => 'Failed to create PayPal order.',
                    ];
                }

                WalletHistory::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'payment_type' => $paymentType,
                    'status' => 'Pending',
                    'transaction_id' => $response['id'],
                    'transaction_date' => now(),
                ]);

                $links = [];

                if (isset($response['links']) && is_array($response['links'])) {
                    $links = $response['links'];
                }

                $paypalUrl = collect($links)->firstWhere('rel', 'approve')['href'] ?? null;

                if (! $paypalUrl) {
                    return response()->json([
                        'code' => 500,
                        'message' => 'Failed to generate PayPal payment link.',
                    ]);
                }

                $responseJson = [
                    'code' => 200,
                    'message' => 'PayPal payment initiated. Redirecting...',
                    'paypal_url' => $paypalUrl,
                ];
            }

            if (strtolower($request->payment_type) === 'stripe') {
                Stripe::setApiKey(config('services.stripe.secret'));
                $currency = 'USD';

                $session = Session::create([
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => $currency,
                                'product_data' => [
                                    'name' => 'Wallet Top-up',
                                ],
                                'unit_amount' => intval($amount * 100),
                            ],
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',
                    'success_url' => route('user.stripe.payment.success.wallet') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment-failed'),
                ]);

                WalletHistory::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'payment_type' => $paymentType,
                    'status' => 'Pending',
                    'transaction_id' => $session->id,
                    'transaction_date' => now(),
                ]);

                $responseJson = [
                    'code' => 200,
                    'message' => 'Stripe payment initiated. Redirecting...',
                    'stripe_url' => $session->url,
                ];
            }

        } catch (\Exception $e) {
            $responseJson = [
                'code' => 500,
                'message' => 'Wallet funding failed: ' . $e->getMessage(),
            ];
        }

        return response()->json($responseJson, $responseJson['code'] ?? 200);
    }

    public function paypalPaymentSuccessWallet(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $accessToken = $this->provider->getAccessToken();

            $result = null;

            if (! $accessToken) {
                $result = response()->json([
                    'code' => 401,
                    'message' => 'PayPal authentication failed.',
                ], 401);
            } else {
                $response = $this->provider->capturePaymentOrder($request->get('token'));

                if ($response instanceof \Psr\Http\Message\StreamInterface) {
                    $response = json_decode((string) $response->getContents(), true);
                } elseif (is_string($response)) {
                    $response = json_decode($response, true);
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === 'COMPLETED') {
                    WalletHistory::where('transaction_id', $response['id'])
                        ->update(['status' => 'Completed']);

                    return redirect()->route('user.wallet', [
                        'transaction_id' => $response['id'],
                    ]);
                }

                $result = response()->json([
                    'code' => 400,
                    'message' => 'Wallet payment capture failed.',
                ], 400);
            }
        } catch (\Exception $e) {
            $result = response()->json([
                'code' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }

        return $result;
    }


    public function stripePaymentSuccessWallet(Request $request): RedirectResponse|JsonResponse
    {
        try {
            Stripe::setApiKey(config('stripe.test.sk'));

            $sessionId = $request->get('session_id');

            WalletHistory::where('transaction_id', $sessionId)
                ->update(['status' => 'Completed']);

            return redirect()->route('user.wallet', ['transaction_id' => $sessionId]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function walletHistoryList(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if (! $user) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $walletHistory = WalletHistory::where('user_id', $user->id)
                ->orderByDesc('transaction_date')
                ->get();

            $totalCredit = WalletHistory::where('user_id', $user->id)
                ->where('status', 'Completed')
                ->where('type', '1')
                ->sum('amount');

            $totalDebit = WalletHistory::where('user_id', $user->id)
                ->where('status', 'Completed')
                ->where('type', '2')
                ->sum('amount');

            $currencySymbol = getDefaultCurrencySymbol();

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Wallet transaction history retrieved successfully.',
                'data' => $walletHistory,
                'total_credit' => $totalCredit,
                'total_debit' => $totalDebit,
                'total_balance' => $totalCredit - $totalDebit,
                'currency_symbol' => $currencySymbol,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'An error occurred while retrieving wallet history.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
