<?php

namespace Modules\Gigs\Repositories\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Booking\Models\Booking;

interface OrderRepositoryInterface
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
    public function orderList(Request $request): array;

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
    public function orderDetails(Request $request): array;

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
    public function orderfile(Request $request): array;

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
    public function orderStatus(Request $request): array;

    /**
     * Get list of buyers for a seller
     *
     * @param Request $request
     *
     * @return Collection<int, array{id: int, full_name: string, profile_image: string|null}>
     */
    public function buyerList(Request $request): Collection;

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
    public function purchaseList(Request $request): array;

    /**
     * @return array<string, mixed>
     */
    public function purchaseDetails(Request $request): array;

    /**
     * Handle buyer order file upload
     *
     * @param Request $request
     *
     * @return array<string, int|string>
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function buyerOrderfile(Request $request): array;

    /**
     * Cancel a booking
     *
     * @param Request $request
     *
     * @return array<string, mixed>
     */

    public function purchaseDelate(Request $request): array;

    /**
     * Get list of sellers for a buyer
     *
     * @param Request $request
     *
     * @return Collection<int, array{id: int, full_name: string, profile_image: string|null}>
     */
    public function sellerList(Request $request): Collection;
}
