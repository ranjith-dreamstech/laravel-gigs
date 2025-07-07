<?php

namespace Modules\Gigs\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\GigsMeta;
use App\Models\OrderData;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\Gigs\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Get list of orders
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     data?: \Illuminate\Database\Eloquent\Collection<int, \Modules\Booking\Models\Booking>,
     *     error?: string
     * }
     */
    public function orderList(Request $request): array
    {
        $orderBy = $request->order_by ?? 'desc';
        $search = $request->input('search');

        try {
            /** @var \App\Models\User|null $auth */
            $auth = current_user();

            if (! $auth) {
                return [
                    'code' => 401,
                    'message' => __('auth.unauthenticated'),
                ];
            }

            /** @var \Illuminate\Database\Eloquent\Builder<\Modules\Booking\Models\Booking> $query */
            $query = Booking::orderBy('id', $orderBy)
                ->where('seller_id', $auth->id);

            if (! empty($search)) {
                $query->where('order_id', 'LIKE', "%{$search}%");
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();

                $query->whereBetween('booking_date', [$start, $end]);
            }

            if (! empty($request->status)) {
                $query->where('booking_status', $request->status);
            }

            if (! empty($request->buyer_id)) {
                $query->where('customer_id', $request->buyer_id);
            }

            /** @var \Illuminate\Database\Eloquent\Collection<int, \Modules\Booking\Models\Booking> $data */
            $data = $query->with([
                'gigs:id,title',
                'user:id,name',
                'user.userDetail:id,user_id,profile_image,first_name,last_name',
            ])->get();

            return [
                'code' => 200,
                'message' => __('admin.general_settings.bank_retrive_success'),
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get order details
     *
     * @param Request $request
     *
     * @return array{
     *     status: bool,
     *     code: int,
     *     data: array{
     *         gig: array{
     *             id: int,
     *             title: string,
     *             days: int,
     *             image: string|null
     *         },
     *         quantity: int,
     *         base_price_total: float,
     *         extra_services?: array<array{
     *             id: int,
     *             name: string,
     *             days: int,
     *             price: float
     *         }>,
     *         extra_services_total?: float,
     *         fast_service?: array{
     *             title: string,
     *             days: int,
     *             price: float,
     *             total: float
     *         },
     *         provide_info: array{
     *             username: string,
     *             first_name: string,
     *             last_name: string,
     *             address: string|null,
     *             profile_image: string|null,
     *             review_count: int,
     *             rating: float
     *         },
     *         currency: string,
     *         final_price: float,
     *         booking_id: int,
     *         buyer_id: int
     *     }
     * }
     */
    public function orderDetails(Request $request): array
    {
        $data = $request->validate([
            'gigs_id' => 'required|integer|exists:gigs,id',
        ]);

        /** @var \App\Models\Gigs|null $gig */
        $gig = Gigs::find($data['gigs_id']);
        if (! $gig) {
            return [
                'status' => false,
                'code' => 404,
                'message' => 'Gig not found',
            ];
        }

        /** @var \Modules\Booking\Models\Booking|null $bookingData */
        $bookingData = Booking::where('id', $request->booking_id)->first();
        if (! $bookingData) {
            return [
                'status' => false,
                'code' => 404,
                'message' => 'Booking not found',
            ];
        }

        $quantity = $bookingData->quantity ?? 0;
        $finalPrice = ($bookingData->gigs_price ?? 0) * $quantity;

        /** @var \App\Models\GigsMeta|null $meta */
        $meta = GigsMeta::where('gig_id', $gig->id)
            ->where('key', 'gigs_image')
            ->first();

        $images = json_decode($meta->value ?? '[]', true);
        $firstImageUrl = ! empty($images) ? asset('storage/' . $images[0]) : null;

        $response = [
            'gig' => [
                'id' => $gig->id,
                'title' => $gig->title ?? '',
                'days' => $gig->days ?? 0,
                'image' => $firstImageUrl,
            ],
            'quantity' => $quantity,
            'base_price_total' => ($bookingData->gigs_price ?? 0) * $quantity,
        ];

        // Handle extra services
        if (! empty($bookingData->extra_service)) {
            $extraServiceIds = explode(',', $bookingData->extra_service);
            $extras = DB::table('gigs_extra')
                ->whereIn('id', $extraServiceIds)
                ->get();

            $extraServices = [];
            $extraTotal = 0;

            foreach ($extras as $extra) {
                $extraServices[] = [
                    'id' => $extra->id,
                    'name' => $extra->name ?? '',
                    'days' => $extra->days ?? 0,
                    'price' => $extra->price ?? 0,
                ];
                $extraTotal += $extra->price ?? 0;
            }

            $response['extra_services'] = $extraServices;
            $response['extra_services_total'] = $extraTotal;
            $finalPrice += $extraTotal;
        }

        // Handle fast service
        if (! empty($bookingData->gigs_fast_price) && $bookingData->gigs_fast_price !== 0) {
            $fastTotal = $gig->fast_service_price ?? 0;
            $finalPrice += $fastTotal;

            $response['fast_service'] = [
                'title' => $gig->fast_service_tile ?? '',
                'days' => $gig->fast_service_days ?? 0,
                'price' => $gig->fast_service_price ?? 0,
                'total' => $fastTotal,
            ];
        }

        // Handle provider info
        /** @var \App\Models\User|null $user */
        $user = User::find($gig->user_id);
        /** @var \App\Models\UserDetail|null $userDetails */
        $userDetails = $user ? UserDetail::where('user_id', $gig->user_id)->first() : null;

        $firstName = $userDetails->first_name ?? $user->name ?? '';
        $lastName = $userDetails->last_name ?? '';
        $profileImage = $userDetails->profile_image ?? null;

        $username = $firstName ? $firstName : ($user->name ?? '');
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

        // Handle currency
        /** @var \Modules\GeneralSetting\Models\GeneralSetting|null $currencyId */
        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        /** @var \Modules\GeneralSetting\Models\Currency|null $currency */
        $currency = $currencyId ? Currency::where('id', $currencyId->value)->first() : null;

        $response['currency'] = $currency->symbol ?? '$';
        $response['final_price'] = $finalPrice;
        $response['booking_id'] = $bookingData->id;
        $response['buyer_id'] = $bookingData->customer_id;

        return [
            'status' => true,
            'code' => 200,
            'data' => $response,
        ];
    }

    /**
     * Handle order file upload
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     data?: array<string, mixed>
     * }
     */
    public function orderfile(Request $request): array
    {
        $auth = current_user();

        /** @var \App\Models\User|null $authUser */
        $authUser = $auth instanceof \App\Models\User ? $auth : null;

        if (! $authUser) {
            return [
                'code' => 401,
                'message' => 'Unauthorized',
            ];
        }

        $request->validate([
            'booking_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'gig_id' => 'required|integer',
            'file_data' => 'required|file|max:5120',
        ]);

        $response = [
            'code' => 200,
            'message' => 'File uploaded and order data stored successfully.',
        ];

        /** @var \App\Models\Gigs|null $gig */
        $gig = Gigs::find($request->gig_id);
        if (! $gig) {
            $response['code'] = 404;
            $response['message'] = 'Gig not found';
            return $response;
        }

        $existingUploads = OrderData::where('booking_id', $request->booking_id)
            ->where('gigs_id', $request->gig_id)
            ->where('uploaded_by', 'seller')
            ->count();

        if ($existingUploads >= ($gig->no_revisions ?? 0)) {
            $response['code'] = 422;
            $response['message'] = 'Maximum number of revisions reached.';
            return $response;
        }

        if (! $request->hasFile('file_data')) {
            $response['code'] = 422;
            $response['message'] = 'No file uploaded.';
            return $response;
        }

        $file = $request->file('file_data');
        $filePath = $file->store('/seller_orderFiles', 'public');
        if ($filePath === false) {
            $response['code'] = 500;
            $response['message'] = 'File storage failed';
            return $response;
        }

        $fileType = $file->getClientOriginalExtension();

        $orderData = new OrderData();
        $orderData->uploaded_by = 'seller';
        $orderData->gigs_id = $request->gig_id;
        $orderData->booking_id = $request->booking_id;
        $orderData->buyer_id = $request->buyer_id;
        $orderData->data = $filePath;
        $orderData->file_type = strtolower($fileType);
        $orderData->created_by = $auth->id;
        $orderData->save();

        return $response;
    }

    /**
     * Update order status
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function orderStatus(Request $request): array
    {
        try {
            $bookingId = $request->booking_id;

            /** @var Booking|null $order */
            $order = Booking::where('id', $bookingId)->first();

            if (! $order) {
                return [
                    'code' => 404,
                    'message' => 'Order not found',
                ];
            }

            $order->booking_status = $request->booking_status;
            $order->save();

            return [
                'code' => 200,
                'message' => 'Order status updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'Failed to update order status',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get list of buyers for a seller
     *
     * @param Request $request
     *
     * @return Collection<int, array{id: int, full_name: string, profile_image: string|null}>
     */
    public function buyerList(Request $request): Collection
    {
        $seller_id = $request->seller_id;
        $search = $request->search;

        /** @var Collection<int, Booking> $bookings */
        $bookings = Booking::where('seller_id', $seller_id)->get();
        $buyerIds = $bookings->pluck('customer_id')->unique();

        /** @var Collection<int, User> $sellers */
        $sellers = User::whereIn('id', $buyerIds)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('userDetail', function ($q2) use ($search) {
                            $q2->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->with('userDetail')
            ->get();

        return $sellers->map(function ($user) {
            return [
                'id' => $user->id,
                'full_name' => $user->name . ' (' . optional($user->userDetail)->first_name . ' ' . optional($user->userDetail)->last_name . ')',
                'profile_image' => $user->userDetail->profile_image ?? null,
            ];
        });
    }

    /**
     * Get purchase list for authenticated user
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     data?: Collection<int, Booking>,
     *     error?: string
     * }
     *
     * @throws \Exception
     */
    public function purchaseList(Request $request): array
    {
        try {
            /** @var \App\Models\User $auth */
            $auth = current_user();

            if (! $auth) {
                return [
                    'code' => 401,
                    'message' => __('auth.unauthenticated'),
                ];
            }

            /** @var \Illuminate\Database\Eloquent\Builder<Booking> $query */
            $query = Booking::orderBy('id', $request->order_by ?? 'desc')
                ->where('customer_id', $auth->id);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();

                $query->whereBetween('booking_date', [$start, $end]);
            }

            // Filter by booking status
            if ($request->filled('status')) {
                $query->where('booking_status', $request->status);
            }

            // Filter by seller ID
            if ($request->filled('seller_id')) {
                $query->where('seller_id', $request->seller_id);
            }

            // Filter by payment method
            if ($request->filled('payment_method')) {
                $method = strtolower($request->payment_method);
                $query->where('payment_type', $method);
            }

            // Eager load relationships
            /** @var Collection<int, Booking> $data */
            $data = $query->with([
                'gigs:id,title',
                'orderData:id,booking_id,data,file_type',
                'user:id,name',
                'user.userDetail:id,user_id,profile_image,first_name,last_name',
                'seller:id,name',
                'seller.seleterDetail:id,user_id,profile_image,first_name,last_name',
            ])->get();

            return [
                'code' => 200,
                'message' => __('admin.general_settings.bank_retrive_success'),
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function purchaseDetails(Request $request): array
    {
        $data = $request->all();

        /** @var Gigs|null $gig */
        $gig = Gigs::find($data['gigs_id'] ?? 0);
        if (! $gig) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Gig not found',
            ];
        }

        /** @var Booking|null $bookingData */
        $bookingData = Booking::where('id', $request->booking_id)->first();
        if (! $bookingData) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Booking not found',
            ];
        }

        $quantity = $bookingData->quantity ?? 0;
        $finalPrice = ($bookingData->gigs_price ?? 0) * $quantity;

        $meta = GigsMeta::where('gig_id', $gig->id)
            ->where('key', 'gigs_image')
            ->first();

        $images = json_decode($meta->value ?? '[]', true);
        $firstImageUrl = isset($images[0]) ? asset('storage/' . $images[0]) : null;

        $response = [
            'gig' => [
                'id' => $gig->id,
                'title' => $gig->title ?? '',
                'days' => $gig->days ?? 0,
                'image' => $firstImageUrl,
            ],
            'quantity' => $quantity,
            'base_price_total' => ($bookingData->gigs_price ?? 0) * $quantity,
        ];

        $extraServices = [];
        $extraTotal = 0;

        if (! empty($bookingData['extra_service'])) {
            $extraServiceIds = explode(',', $bookingData['extra_service']);

            $extras = DB::table('gigs_extra')
                ->whereIn('id', $extraServiceIds)
                ->get();

            foreach ($extras as $extra) {
                $extraServices[] = [
                    'id' => $extra->id,
                    'name' => $extra->name ?? '',
                    'days' => $extra->days ?? 0,
                    'price' => $extra->price ?? 0,
                ];
                $extraTotal += $extra->price ?? 0;
            }

            $response['extra_services'] = $extraServices;
            $response['extra_services_total'] = $extraTotal;
            $finalPrice += $extraTotal;
        }

        // Fixed strict comparison
        if (isset($bookingData->gigs_fast_price) && $bookingData->gigs_fast_price > 0) {
            $fastTotal = $gig->fast_service_price;
            $finalPrice += $fastTotal;

            $response['fast_service'] = [
                'title' => $gig->fast_service_tile ?? '',
                'days' => $gig->fast_service_days ?? 0,
                'price' => $gig->fast_service_price ?? 0,
                'total' => $fastTotal,
            ];
        }

        /** @var User|null $user */
        $user = User::find($gig->user_id);
        $userDetails = UserDetail::where('user_id', $gig->user_id)->first();

        // Fixed user name access
        $firstName = $userDetails->first_name ?? $user->name ?? '';
        $lastName = $userDetails->last_name ?? '';
        $profileImage = $userDetails->profile_image ?? 'https://www.w3schools.com/howto/img_avatar.png';

        $username = ! empty($firstName) ? $firstName : ($user->name ?? '');
        $lastName = empty($firstName) ? $lastName : '';

        $response['provide_info'] = [
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'address' => $userDetails->address ?? null,
            'profile_image' => $profileImage,
            'review_count' => 20,
            'rating' => 4.5,
        ];

        // Fixed currency access
        $currencySymbol = '$';
        $currencySetting = GeneralSetting::where('key', 'currency_symbol')->first();

        if ($currencySetting && $currencySetting->value) {
            $currency = Currency::where('id', $currencySetting->value)->first();
            $currencySymbol = $currency->symbol ?? '$';
        }

        $response['currency'] = $currencySymbol;
        $response['final_price'] = $finalPrice;
        $response['booking_id'] = $bookingData->id;
        $response['buyer_id'] = $bookingData->customer_id;

        return [
            'code' => 200,
            'status' => true,
            'data' => $response,
        ];
    }

    /**
     * Handle buyer order file upload
     *
     * @param Request $request
     *
     * @return array<string, int|string>
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function buyerOrderfile(Request $request): array
    {
        /** @var \App\Models\User $auth */
        $auth = current_user();

        $response = [
            'code' => 200,
            'message' => 'File uploaded and order data stored successfully.',
        ];

        $request->validate([
            'booking_id' => 'required|integer',
            'buyer_ids' => 'required|integer',
            'gig_id' => 'required|integer',
            'file_data' => 'required|file|max:5120',
        ]);

        /** @var \App\Models\Gigs|null $gig */
        $gig = Gigs::find($request->gig_id);

        if (! $gig) {
            $response['code'] = 404;
            $response['message'] = 'Gig not found.';
            return $response;
        }

        $existingUploads = OrderData::where('booking_id', $request->booking_id)
            ->where('gigs_id', $request->gig_id)
            ->where('uploaded_by', 'buyer')
            ->count();

        if ($existingUploads >= ($gig->no_revisions ?? 0)) {
            $response['code'] = 422;
            $response['message'] = 'Maximum number of revisions reached.';
            return $response;
        }

        if (! $request->hasFile('file_data')) {
            $response['code'] = 422;
            $response['message'] = 'No file uploaded.';
            return $response;
        }

        $file = $request->file('file_data');
        $filePath = $file->store('/buyer_orderFiles', 'public');

        if ($filePath === false) {
            $response['code'] = 500;
            $response['message'] = 'Failed to store file.';
            return $response;
        }

        $fileType = $file->getClientOriginalExtension();

        $orderData = new OrderData();
        $orderData->uploaded_by = 'buyer';
        $orderData->gigs_id = $request->gig_id;
        $orderData->booking_id = $request->booking_id;
        $orderData->buyer_id = $request->buyer_ids;
        $orderData->data = (string) $filePath;
        $orderData->file_type = strtolower($fileType);
        $orderData->created_by = $auth->id;
        $orderData->save();

        return $response;
    }

    /**
     * Cancel a booking
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function purchaseDelate(Request $request): array
    {
        try {
            $bookingId = $request->booking_id;
            $reason = $request->reason;

            /** @var Booking $order */
            $order = Booking::with(['gigs', 'user'])->findOrFail($bookingId);

            // Update booking status and reason
            $order->booking_status = 6;
            $order->cancel_reason = $reason;
            $order->save();

            /** @var \App\Models\User|null $authUser */
            $authUser = Auth::guard('web')->user();
            $companyName = GeneralSetting::where('key', 'organization_name')->value('value') ?? 'Default Company Name';

            /** @var \App\Models\Gigs|null $gig */
            $gig = $order->gigs;

            // Notification data
            $notifyData = [
                'user_name' => $order->user->name ?? '',
                'company_name' => $companyName,
                'email' => $order->user->email ?? '',
                'phonenumber' => $order->user->phone_number ?? '',
                'gigs_name' => $gig->title ?? '',
                'order_id' => $order->order_id ?? '',
                'booking_date' => $order->booking_date ? formatDateTime($order->booking_date) : '',
                'pickup_location' => $order->pickupLocation->name ?? '',
                'return_location' => $order->returnLocation->name ?? '',
                'delivery_by' => $order->delivery_by ?? '',
                'quantity' => $order->quantity ?? '',
                'extra_services' => $order->extra_service ?? '',
                'extra_service_price' => $order->total_extra_service_price ?? '',
                'gigs_price' => $order->gigs_price ?? '',
                'gigs_total_price' => $order->gigs_total_price ?? '',
                'gigs_fast_price' => $order->gigs_fast_price ?? '',
                'final_price' => $order->final_price ?? '',
                'payment_type' => $order->payment_type ?? '',
                'payment_status' => $order->payment_status ?? '',
                'transaction_id' => $order->transaction_id ?? '',
                'cancel_reason' => $reason,
            ];

            if (gigNotificationEnabled() && $authUser) {
                /** @var User|null $appAdmin */
                $appAdmin = User::where('user_type', 1)->first();
                if ($appAdmin) {
                    $notifyData['related_user_id'] = $authUser->id;
                    sendNotification($appAdmin->email, 'booking-cancelled-to-admin', $notifyData);
                }

                /** @var User|null $appSeller */
                $appSeller = User::where('id', $gig?->user_id)->first();
                if ($appSeller) {
                    $notifyData['related_user_id'] = $authUser->id;
                    sendNotification($appSeller->email, 'booking-cancelled-to-seller', $notifyData);
                }

                if ($order->user && $gig) {
                    $notifyData['related_user_id'] = $gig->user_id;
                    sendNotification($order->user->email, 'booking-cancelled-to-user', $notifyData);
                }
            }

            return [
                'code' => 200,
                'status' => true,
                'message' => 'Order cancelled successfully',
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get list of sellers for a buyer
     *
     * @param Request $request
     *
     * @return Collection<int, array{id: int, full_name: string, profile_image: string|null}>
     */
    public function sellerList(Request $request): Collection
    {
        $buyer_id = $request->buyer_id;
        $search = $request->search;

        $bookings = Booking::where('customer_id', $buyer_id)->get();
        $sellerIds = $bookings->pluck('seller_id')->unique()->toArray();

        /** @var Collection<int, User> $sellers */
        $sellers = User::whereIn('id', $sellerIds)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('userDetail', function ($q2) use ($search) {
                            $q2->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->with('userDetail')
            ->get();

        return $sellers->map(function (User $user) {
            $firstName = $user->userDetail->first_name ?? '';
            $lastName = $user->userDetail->last_name ?? '';
            $profileImage = $user->userDetail->profile_image ?? null;

            return [
                'id' => $user->id,
                'full_name' => $user->name . ' (' . $firstName . ' ' . $lastName . ')',
                'profile_image' => $profileImage,
            ];
        });
    }
}
