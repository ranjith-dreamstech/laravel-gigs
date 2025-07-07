<?php

namespace Modules\Booking\Repositories\Contracts;

use App\Models\City;
use App\Models\Country;
use App\Models\Gigs;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingUserInfo;

interface UserBookingRepositoryInterface
{
    /**
     * @return array{
     *     status: bool,
     *     message?: string,
     *     data?: array{
     *         gig: array{id: int, title: string, days: int, image: string|null},
     *         quantity: int,
     *         base_price_total: float,
     *         extra_services?: array<array{id: int, name: string, days: int, price: float}>,
     *         extra_services_total?: float,
     *         fast_service?: array{title: string, days: int, price: float, total: float},
     *         provide_info: array{
     *             username: string|null,
     *             first_name: string,
     *             last_name: string,
     *             address: string|null,
     *             profile_image: string,
     *             review_count: int,
     *             rating: float
     *         },
     *         currency: string,
     *         final_price: float
     *     }
     * }
     */
    public function createBooking(Request $request): array;

    /**
     * @return array{
     *     slug: string,
     *     gigs?: Gigs,
     *     firstImageUrl: string|null,
     *     countries: EloquentCollection<int, Country>,
     *     user?: User|null,
     *     currencySymbol: string,
     *     error?: string
     * }
     */
    public function index(string $slug): array;

    /**
     * @return Collection<int, State>
     */
    public function getStates(int $country_id): Collection;

    /**
     * @return Collection<int, City>
     */
    public function getCities(int $state_id): Collection;

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
    public function userPayments(Request $request): array;

    /**
     * @return array{
     *     redirect: bool,
     *     url?: string,
     *     code?: int,
     *     message?: string
     * }
     */
    public function paypalPaymentSuccess(Request $request): array;

    /**
     * @return array{
     *     redirect: bool,
     *     url?: string,
     *     code?: int,
     *     message?: string
     * }
     */
    public function stripPaymentSuccess(Request $request): array;

    /**
     * @return array{
     *     transaction_id: string,
     *     booking: Booking|null,
     *     gigs: Gigs|null,
     *     firstImageUrl: string|null,
     *     bookingInfo: BookingUserInfo|null,
     *     currencySymbol: string,
     *     error?: string
     * }
     */
    public function paymentSuccess(string $transaction_id): array;
}
