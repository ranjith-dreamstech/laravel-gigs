<?php

namespace Modules\Booking\Repositories\Eloquent;

use App\Models\City;
use App\Models\Country;
use App\Models\Gigs;
use App\Models\GigsMeta;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingUserInfo;
use Modules\Booking\Repositories\Contracts\UserBookingRepositoryInterface;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class UserBookingRepository implements UserBookingRepositoryInterface
{
    private PayPalClient $provider;

    private const STORAGE_PATH = 'storage/';
    public function __construct()
    {
        $this->provider = new PayPalClient();
        $this->provider->getAccessToken();
    }

    public function createBooking(Request $request): array
    {
        $data = $request->validate([
            'gigs_id' => 'required|integer|exists:gigs,id',
            'gigs_price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:1',
            'extra_service' => 'nullable|array',
            'extra_service.*' => 'integer|exists:gigs_extra,id',
            'fast_service' => 'nullable|in:yes,no',
        ]);

        /** @var Gigs $gig */
        $gig = Gigs::find($data['gigs_id']);
        if (! $gig) {
            return [
                'status' => false,
                'message' => __('web.home.gig_not_found'),
            ];
        }

        $quantity = $request->quantity ?? 1;
        $finalPrice = $data['gigs_price'] * $quantity;

        // Get gig image
        $meta = GigsMeta::where('gig_id', $gig->id)
            ->where('key', 'gigs_image')
            ->first();

        $images = json_decode($meta->value ?? '[]', true);
        $firstImageUrl = optional($images)[0]
            ? asset(self::STORAGE_PATH . $images[0])
            : null;
        $response = [
            'gig' => [
                'id' => $gig->id,
                'title' => $gig->title,
                'days' => $gig->days,
                'image' => $firstImageUrl,
            ],
            'quantity' => $quantity,
            'base_price_total' => $data['gigs_price'] * $quantity,
        ];

        $extraServices = [];
        $extraTotal = 0;

        if (! empty($data['extra_service'])) {
            $extras = DB::table('gigs_extra')
                ->whereIn('id', $data['extra_service'])
                ->get();

            foreach ($extras as $extra) {
                $extraServices[] = [
                    'id' => $extra->id,
                    'name' => $extra->name,
                    'days' => $extra->days,
                    'price' => $extra->price,
                ];
                $extraTotal += $extra->price;
            }

            $response['extra_services'] = $extraServices;
            $response['extra_services_total'] = $extraTotal;
            $finalPrice += $extraTotal;
        }

        if ($data['fast_service'] === 'yes') {
            $fastTotal = $gig->fast_service_price;
            $finalPrice += $fastTotal;

            $response['fast_service'] = [
                'title' => $gig->fast_service_tile,
                'days' => $gig->fast_service_days,
                'price' => $gig->fast_service_price,
                'total' => $fastTotal,
            ];
        }

        /** @var User $user */
        $user = User::find($gig->user_id);
        $userDetails = UserDetail::where('user_id', $gig->user_id)->first();
        $firstName = $userDetails->first_name ?? $user->name;
        $lastName = $userDetails->last_name ?? '';
        $profileImage = $userDetails->profile_image ?? null;

        $username = $firstName ? $firstName : ($user->name ?? null);
        $lastName = $firstName ? '' : $lastName;
        $profileImage = $profileImage ? $profileImage : 'https://www.w3schools.com/howto/img_avatar.png';

        $response['provide_info'] = [
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'address' => $userDetails->address ?? null,
            'profile_image' => $profileImage,
            'review_count' => 20,
            'rating' => 4.5,
        ];

        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        if ($currencyId) {
            $currency = Currency::where('id', $currencyId->value)->first();
            $currencySymbol = $currency->symbol ?? '$';
            $response['currency'] = $currencySymbol;
        } else {
            $response['currency'] = '$';
        }

        $response['final_price'] = $finalPrice;

        return [
            'status' => true,
            'data' => $response,
        ];
    }

    /**
     * @return array{
     *     slug: string,
     *     gigs?: \App\Models\Gigs,
     *     firstImageUrl: string|null,
     *     countries: \Illuminate\Database\Eloquent\Collection,
     *     user?: \App\Models\User|null,
     *     currencySymbol: string,
     *     error?: string
     * }
     */
    public function index(string $slug): array
    {
        /** @var Gigs|null $gigs */
        $gigs = Gigs::select('id', 'general_price', 'title')->where('slug', $slug)->first();
        if (! $gigs) {
            return [
                'slug' => $slug,
                'error' => __('web.home.gig_not_found'),
            ];
        }

        $meta = GigsMeta::where('gig_id', $gigs->id)
            ->where('key', 'gigs_image')
            ->first();
        $user = Auth::guard('web')->user();

        $countries = Country::get();
        $images = json_decode($meta->value ?? '[]', true);
        $firstImageUrl = !empty($images)
            ? asset(self::STORAGE_PATH . $images[0])
            : null;
        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        $currencySymbol = '$';
        if ($currencyId) {
            $currency = Currency::where('id', $currencyId->value)->first();
            $currencySymbol = $currency->symbol ?? '$';
        }

        return [
            'slug' => $slug,
            'gigs' => $gigs,
            'firstImageUrl' => $firstImageUrl,
            'countries' => $countries,
            'user' => $user,
            'currencySymbol' => $currencySymbol,
        ];
    }

    /**
     * @return Collection<int, State>
     */
    public function getStates(int $country_id): Collection
    {
        return State::where('country_id', $country_id)->get(['id', 'name']);
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(int $state_id): Collection
    {
        return City::where('state_id', $state_id)->get(['id', 'name']);
    }

    /**
     * @return array{
     *     code: int,
     *     message?: string,
     *     error?: string,
     *     cod?: string,
     *     redirect_url?: string,
     *     paypal_url?: string,
     *     stripurl?: string
     * }
     */
    public function userPayments(Request $request): array
    {
        $responseData = [];

        $total = $request->input('gig_price') * ($request->input('gig_quantity') ?? 1);
        $formattedBookingDate = Carbon::now()->format('Y-m-d H:i:s');

        /** @var Gigs|null $gig */
        $gig = Gigs::find($request->input('gig_id'));
        if ($gig === null) {
            return [
                'code' => 404,
                'error' => __('web.home.gig_not_found'),
            ];
        }

        $deliveryDays = $gig->days ?? 0;

        if ($request->filled('gig_fast_service_total')) {
            $deliveryDays += $gig->fast_service_days ?? 0;
        }

        if ($request->filled('extra_service_ids')) {
            $extraServiceIds = explode(',', $request->input('extra_service_ids'));

            $extraDays = DB::table('gigs_extra')
                ->whereIn('id', $extraServiceIds)
                ->sum('days');

            $deliveryDays += $extraDays;
        }

        $deliveryByDate = Carbon::parse($formattedBookingDate)
            ->addDays($deliveryDays)
            ->format('Y-m-d H:i:s');

        /** @var User|null $user */
        $user = Auth::guard('web')->user();

        $paymentType = $request->payment_type;

        if ($paymentType === 'cod') {
            $generateID = 'COD' . str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $data = [
                'gigs_id' => $request->input('gig_id'),
                'booking_status' => Booking::$inprogress,
                'booking_date' => $formattedBookingDate,
                'delivery_by' => $deliveryByDate,
                'quantity' => $request->input('gig_quantity') ?? 1,
                'customer_id' => $user?->id,
                'category_id' => $request->category_id ?? null,
                'seller_id' => $gig->user_id,
                'extra_service' => $request->input('extra_service_ids'),
                'total_extra_service_price' => $request->input('gig_extra_service_total'),
                'gigs_price' => $request->input('gig_price'),
                'gigs_total_price' => $total,
                'gigs_fast_price' => $request->input('gig_fast_service_total'),
                'final_price' => $request->input('final_price'),
                'cancel_date' => $request->input('cancel_date') ?? null,
                'cancel_by' => $request->input('cancel_by') ?? null,
                'cancel_reason' => $request->input('cancel_reason') ?? null,
                'created_by' => $request->input('created_by') ?? null,
                'updated_by' => $request->input('updated_by') ?? null,
                'transaction_id' => $generateID,
                'payment_status' => Booking::$unpaid,
                'payment_type' => 'wallet',
            ];

            /** @var Booking $booking */
            $booking = Booking::create($data);
            $reservationId = 'GIG' . str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT);
            $booking->update(['order_id' => $reservationId]);

            $addData = [
                'booking_id' => $booking->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'category_id' => $request->category_id ?? null,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->postal_code,
                'email' => $request->email,
            ];

            BookingUserInfo::create($addData);

            $responseData = [
                'code' => 200,
                'message' => 'Booking successfully created!',
                'cod' => $booking->transaction_id,
                'redirect_url' => route('payment.success.page', ['transaction_id' => $booking->transaction_id]),
            ];
        } elseif ($paymentType === 'paypal') {
            $currency_details = 'USD';
            $purchase_units = [];

            $unit = [
                'items' => [
                    [
                        'name' => 'Rental System',
                        'quantity' => 1,
                        'unit_amount' => [
                            'currency_code' => $currency_details,
                            'value' => $request->final_price,
                        ],
                    ],
                ],
                'amount' => [
                    'currency_code' => $currency_details,
                    'value' => $request->final_price,
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => $currency_details,
                            'value' => $request->final_price,
                        ],
                    ],
                ],
            ];

            $purchase_units[] = $unit;
            $order = [
                'intent' => 'CAPTURE',
                'purchase_units' => $purchase_units,
                'application_context' => [
                    'return_url' => url('paypal-payment-success'),
                    'cancel_url' => url('payment-failed'),
                ],
            ];

            $response = $this->provider->createOrder($order);

            if (!isset($response['id'])) {
                $responseData = [
                    'code' => 500,
                    'message' => 'Failed to create PayPal order.',
                ];
            } elseif (!isset($response['links'][1]['href'])) {
                $responseData = [
                    'code' => 500,
                    'message' => 'Failed to generate PayPal payment link.',
                ];
            } else {
                $data = [
                    'gigs_id' => $request->input('gig_id'),
                    'booking_status' => Booking::$inprogress,
                    'booking_date' => $formattedBookingDate,
                    'delivery_by' => $deliveryByDate,
                    'quantity' => $request->input('gig_quantity') ?? 1,
                    'customer_id' => $user?->id,
                    'seller_id' => $gig->user_id,
                    'extra_service' => $request->input('extra_service_ids'),
                    'total_extra_service_price' => $request->input('gig_extra_service_total'),
                    'gigs_price' => $request->input('gig_price'),
                    'gigs_total_price' => $total,
                    'gigs_fast_price' => $request->input('gig_fast_service_total'),
                    'final_price' => $request->input('final_price'),
                    'cancel_date' => $request->input('cancel_date') ?? null,
                    'cancel_by' => $request->input('cancel_by') ?? null,
                    'cancel_reason' => $request->input('cancel_reason') ?? null,
                    'created_by' => $request->input('created_by') ?? null,
                    'updated_by' => $request->input('updated_by') ?? null,
                    'transaction_id' => $response['id'],
                    'payment_status' => Booking::$unpaid,
                    'payment_type' => 'paypal',
                ];

                $booking = Booking::create($data);
                $reservationId = 'GIG' . str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT);
                $booking->update(['order_id' => $reservationId]);

                $addData = [
                    'booking_id' => $booking->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'category_id' => $request->category_id ?? null,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'pincode' => $request->postal_code,
                    'email' => $request->email,
                ];

                BookingUserInfo::create($addData);

                $responseData = [
                    'code' => 200,
                    'message' => 'Order created successfully.',
                    'paypal_url' => $response['links'][1]['href'],
                ];
            }
        } elseif ($paymentType === 'stripe') {
            Stripe::setApiKey(config('services.stripe.secret'));
            $currency_details = 'USD';

            /** @var Session $session */
            $session = Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $currency_details,
                            'product_data' => ['name' => 'Rental Services'],
                            'unit_amount' => intval($request->final_price * 100),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('strip.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            ]);

            $data = [
                'gigs_id' => $request->input('gig_id'),
                'booking_status' => Booking::$inprogress,
                'booking_date' => $formattedBookingDate,
                'delivery_by' => $deliveryByDate,
                'quantity' => $request->input('gig_quantity') ?? 1,
                'customer_id' => $user?->id,
                'seller_id' => $gig->user_id,
                'extra_service' => $request->input('extra_service_ids'),
                'total_extra_service_price' => $request->input('gig_extra_service_total'),
                'gigs_price' => $request->input('gig_price'),
                'gigs_total_price' => $total,
                'gigs_fast_price' => $request->input('gig_fast_service_total'),
                'final_price' => $request->input('final_price'),
                'cancel_date' => $request->input('cancel_date') ?? null,
                'cancel_by' => $request->input('cancel_by') ?? null,
                'cancel_reason' => $request->input('cancel_reason') ?? null,
                'created_by' => $request->input('created_by') ?? null,
                'updated_by' => $request->input('updated_by') ?? null,
                'transaction_id' => $session->id,
                'payment_status' => Booking::$unpaid,
                'payment_type' => 'stripe',
            ];

            $booking = Booking::create($data);
            $reservationId = 'GIG' . str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT);
            $booking->update(['order_id' => $reservationId]);

            $addData = [
                'booking_id' => $booking->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'category_id' => $request->category_id ?? null,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->postal_code,
                'email' => $request->email,
            ];

            BookingUserInfo::create($addData);

            $responseData = [
                'code' => 200,
                'message' => 'Order created successfully.',
                'stripurl' => (string) $session->url,
            ];
        } else {
            $responseData = [
                'code' => 400,
                'message' => 'Invalid payment type',
            ];
        }

        return $responseData;
    }

    /**
     * @return array{
     *     redirect: bool,
     *     url?: string,
     *     code?: int,
     *     message?: string
     * }
     */
    public function paypalPaymentSuccess(Request $request): array
    {
        $result = [];

        try {
            $response = $this->provider->capturePaymentOrder($request->get('token'));

            if (! is_array($response) || ! isset($response['status']) || $response['status'] !== 'COMPLETED') {
                $result = [
                    'redirect' => false,
                    'code' => 400,
                    'message' => __('web.home.payment_capture_failed'),
                ];
            } elseif (! isset($response['id'])) {
                $result = [
                    'redirect' => false,
                    'code' => 400,
                    'message' => 'Invalid PayPal response',
                ];
            } else {
                Booking::where('transaction_id', $response['id'])->update(['payment_status' => 2]);

                /** @var Booking|null $booking */
                $booking = Booking::with(['gigs', 'user'])->where('transaction_id', $response['id'])->first();

                if (! $booking) {
                    $result = [
                        'redirect' => false,
                        'code' => 404,
                        'message' => 'Booking not found',
                    ];
                } else {
                    $authUser = Auth::guard('web')->user();
                    $companyName = GeneralSetting::where('key', 'organization_name')->value('value') ?? 'Default Company Name';

                    $notifyData = [
                        'user_name' => $booking->user->name ?? '',
                        'company_name' => $companyName,
                        'email' => $booking->user->email ?? '',
                        'phonenumber' => $booking->user->phone_number ?? '',
                        'gigs_name' => $booking->gigs->title ?? '',
                        'order_id' => $booking->order_id ?? '',
                        'booking_date' => $booking->booking_date ? formatDateTime($booking->booking_date) : '',
                        'quantity' => $booking->quantity ?? '',
                        'extra_services' => $booking->extra_service ?? '',
                        'extra_service_price' => $booking->total_extra_service_price ?? '',
                        'gigs_price' => $booking->gigs_price ?? '',
                        'gigs_total_price' => $booking->gigs_total_price ?? '',
                        'gigs_fast_price' => $booking->gigs_fast_price ?? '',
                        'final_price' => $booking->final_price ?? '',
                        'payment_type' => $booking->payment_type ?? '',
                        'payment_status' => $booking->payment_status ?? '',
                        'transaction_id' => $booking->transaction_id ?? '',
                    ];

                    if (gigNotificationEnabled()) {
                        /** @var User|null $appAdmin */
                        $appAdmin = User::where('user_type', 1)->first();
                        if ($appAdmin && $authUser) {
                            $notifyData['related_user_id'] = $authUser->id;
                            sendNotification($appAdmin->email, 'booking-confirmation-to-admin', $notifyData);
                        }

                        /** @var Gigs|null $gigs */
                        $gigs = $booking->gigs;
                        if ($gigs) {
                            /** @var User|null $appSeller */
                            $appSeller = User::where('id', $gigs->user_id)->first();
                            if ($appSeller && $authUser) {
                                $notifyData['related_user_id'] = $authUser->id;
                                sendNotification($appSeller->email, 'booking-confirmation-to-seller', $notifyData);
                            }

                            if ($booking->user) {
                                $notifyData['related_user_id'] = $gigs->user_id;
                                sendNotification($booking->user->email, 'booking-confirmation-to-user', $notifyData);
                            }
                        }
                    }

                    $result = [
                        'redirect' => true,
                        'url' => route('payment.success.page', ['transaction_id' => $response['id']]),
                    ];
                }
            }
        } catch (\Exception $e) {
            $result = [
                'redirect' => false,
                'code' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function stripPaymentSuccess(Request $request): array
    {
        try {
            Stripe::setApiKey(config('stripe.test.sk'));
            $sessionId = $request->get('session_id');
            $authUser = Auth::guard('web')->user();

            Booking::where('transaction_id', $sessionId)->update(['payment_status' => 2]);

            /** @var Booking|null $booking */
            $booking = Booking::with(['gigs', 'user'])
                ->where('transaction_id', $sessionId)
                ->first();

            if (! $booking) {
                return [
                    'code' => 404,
                    'redirect' => false,
                    'message' => 'Booking not found.',
                ];
            }

            $companyName = GeneralSetting::where('key', 'organization_name')->value('value') ?? 'Default Company Name';

            $notifyData = [
                'user_name' => $booking->user->name ?? '',
                'company_name' => $companyName,
                'email' => $booking->user->email ?? '',
                'phonenumber' => $booking->user->phone_number ?? '',
                'gigs_name' => $booking->gigs->title ?? '',
                'order_id' => $booking->order_id ?? '',
                'booking_date' => $booking->booking_date ? formatDateTime($booking->booking_date) : '',
                'quantity' => $booking->quantity ?? '',
                'extra_services' => $booking->extra_service ?? '',
                'extra_service_price' => $booking->total_extra_service_price ?? '',
                'gigs_price' => $booking->gigs_price ?? '',
                'gigs_total_price' => $booking->gigs_total_price ?? '',
                'gigs_fast_price' => $booking->gigs_fast_price ?? '',
                'final_price' => $booking->final_price ?? '',
                'payment_type' => $booking->payment_type ?? '',
                'payment_status' => $booking->payment_status ?? '',
                'transaction_id' => $booking->transaction_id ?? '',
            ];

            if (gigNotificationEnabled()) {
                /** @var User|null $appAdmin */
                $appAdmin = User::where('user_type', 1)->first();
                if ($appAdmin && $authUser) {
                    $notifyData['related_user_id'] = $authUser->id;
                    sendNotification($appAdmin->email, 'booking-confirmation-to-admin', $notifyData);
                }

                /** @var Gigs|null $gigs */
                $gigs = $booking->gigs;
                if ($gigs) {
                    /** @var User|null $appSeller */
                    $appSeller = User::where('id', $gigs->user_id)->first();
                    if ($appSeller && $authUser) {
                        $notifyData['related_user_id'] = $authUser->id;
                        sendNotification($appSeller->email, 'booking-confirmation-to-seller', $notifyData);
                    }

                    if ($booking->user) {
                        $notifyData['related_user_id'] = $gigs->user_id;
                        sendNotification($booking->user->email, 'booking-confirmation-to-user', $notifyData);
                    }
                }
            }

            return [
                'redirect' => true,
                'url' => route('payment.success.page', ['transaction_id' => $sessionId]),
            ];
        } catch (\Exception $e) {
            return [
                'redirect' => false,
                'code' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function paymentSuccess(string $transaction_id): array
    {
        /** @var Booking|null $booking */
        $booking = Booking::where('transaction_id', $transaction_id)->first();
        if (! $booking) {
            return [
                'error' => 'Booking not found',
            ];
        }

        /** @var BookingUserInfo|null $bookingInfo */
        $bookingInfo = BookingUserInfo::where('booking_id', $booking->id)->first();

        /** @var Gigs|null $gigs */
        $gigs = Gigs::where('id', $booking->gigs_id)->first();
        if (! $gigs) {
            return [
                'error' => __('web.home.gig_not_found'),
            ];
        }

        /** @var GigsMeta|null $meta */
        $meta = GigsMeta::where('gig_id', $gigs->id)
            ->where('key', 'gigs_image')
            ->first();

        $images = json_decode($meta->value ?? '[]', true);
        $firstImageUrl = !empty($images) ? asset('storage/' . $images[0]) : null;

        /** @var GeneralSetting|null $currencyId */
        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        $currencySymbol = '$';
        if ($currencyId && $currencyId->value) {
            /** @var Currency|null $currency */
            $currency = Currency::where('id', $currencyId->value)->first();
            $currencySymbol = $currency->symbol ?? '$';
        }

        return [
            'transaction_id' => $transaction_id,
            'booking' => $booking,
            'gigs' => $gigs,
            'firstImageUrl' => $firstImageUrl,
            'bookingInfo' => $bookingInfo,
            'currencySymbol' => $currencySymbol,
        ];
    }
}
