<?php

namespace Modules\Gigs\Repositories\Contracts;

use Illuminate\Http\Request;

interface GigsInterface
{
    /**
     * Get paginated gigs list for authenticated user
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     data: array<array{
     *         id: int,
     *         slug: string,
     *         title: string,
     *         price: float,
     *         days: int,
     *         category_name: string|null,
     *         images: array<string>
     *     }>
     * }
     */
    public function indexGigsList(Request $request): array;

    /**
     * List gigs with filtering and sorting
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     data?: \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs>,
     *     error?: string
     * }
     */
    public function list(Request $request): array;

    /**
     * Get gig addons
     *
     * @param int $gigId
     *
     * @return array{
     *     data: \Illuminate\Support\Collection<int, object{
     *         id: int,
     *         name: string,
     *         price: float,
     *         days: int
     *     }>
     * }
     */
    public function getAddons(int $gigId): array;

    /**
     * Get gig FAQs
     *
     * @param int $gigId
     *
     * @return array{
     *     data: \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs>
     * }
     */
    public function getFaq(int $gigId): array;

    /**
     * Get gig images
     *
     * @param int $gigId
     *
     * @return array{
     *     data: array{
     *         id: int|null,
     *         key: string|null,
     *         value: array<string>
     *     }
     * }
     */
    public function getImage(int $gigId): array;

    /**
     * Store new gig information
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     success: bool,
     *     message: string,
     *     redirect_url: string
     * }
     */
    public function storeGigs(Request $request): array;

    /**
     * Edit gig information
     *
     * @param Request $request
     *
     * @return array{
     *     code: int,
     *     success: bool,
     *     message: string,
     *     redirect_url?: string
     * }
     */
    public function editGigs(Request $request): array;

    /**
     * Get subcategories for a category
     *
     * @param int $categoryId
     *
     * @return array<array{id: int, name: string}>
     */
    public function getSubCategories(int $categoryId): array;

    /**
     * Get list of gigs for API
     *
     * @param Request $request
     *
     * @return array{
     *     status: bool,
     *     message: string,
     *     data: array<array{
     *         id: int,
     *         title: string,
     *         slug: string,
     *         general_price: float,
     *         days: int,
     *         category: string,
     *         location: string,
     *         category_id: int,
     *         sub_category_id: int,
     *         no_revisions: int,
     *         tags: string|null,
     *         currency: string,
     *         description: string,
     *         fast_service_tile: string|null,
     *         fast_service_price: float|null,
     *         fast_service_days: int|null,
     *         fast_dis: string|null,
     *         buyer: string|null,
     *         is_wishlist: bool,
     *         is_feature: bool,
     *         is_hot: bool,
     *         is_recommend: bool,
     *         is_authenticated: bool,
     *         rating: float,
     *         reviews: int,
     *         video_platform: string|null,
     *         video_link: string|null,
     *         status: int,
     *         gig_image: array<string>,
     *         provider_name: string,
     *         provider_image: string|null,
     *         provider_location: string
     *     }>,
     *     category?: \Modules\Category\Models\Categories|null,
     *     sub_categories?: array<int, \Modules\Category\Models\Categories>,
     *     services_count?: int,
     *     pagination?: array{
     *         total: int,
     *         per_page: int,
     *         current_page: int,
     *         last_page: int,
     *         from: int,
     *         to: int,
     *         next_page_url: string|null,
     *         prev_page_url: string|null
     *     }
     * }
     */
    public function recentListApi(Request $request): array;
    /**
     * Get list of gigs for API
     *
     * @param Request $request
     *
     * @return array{
     *     status: bool,
     *     message: string,
     *     data: array<array{
     *         id: int,
     *         title: string,
     *         slug: string,
     *         general_price: float,
     *         days: int,
     *         category: string,
     *         location: string,
     *         category_id: int,
     *         sub_category_id: int,
     *         no_revisions: int,
     *         tags: string|null,
     *         currency: string,
     *         description: string,
     *         fast_service_tile: string|null,
     *         fast_service_price: float|null,
     *         fast_service_days: int|null,
     *         fast_dis: string|null,
     *         buyer: string|null,
     *         is_wishlist: bool,
     *         is_feature: bool,
     *         is_hot: bool,
     *         is_recommend: bool,
     *         is_authenticated: bool,
     *         rating: float,
     *         reviews: int,
     *         video_platform: string|null,
     *         video_link: string|null,
     *         status: int,
     *         gig_image: array<string>,
     *         provider_name: string,
     *         provider_image: string|null,
     *         provider_location: string
     *     }>,
     *     category?: \Modules\Category\Models\Categories|null,
     *     sub_categories?: array<int, \Modules\Category\Models\Categories>,
     *     services_count?: int,
     *     pagination?: array{
     *         total: int,
     *         per_page: int,
     *         current_page: int,
     *         last_page: int,
     *         from: int,
     *         to: int,
     *         next_page_url: string|null,
     *         prev_page_url: string|null
     *     }
     * }
     */
    public function listApi(Request $request): array;

    /**
     * Get gig details by slug for API
     *
     * @param string $slug
     *
     * @return array{
     *     code: int,
     *     success: bool,
     *     message?: string,
     *     data?: array{
     *         id: int,
     *         user_id: int,
     *         slug: string,
     *         title: string,
     *         general_price: float,
     *         days: int,
     *         category: string,
     *         location: string,
     *         category_id: int,
     *         sub_category_id: int,
     *         no_revisions: int,
     *         tags: string|null,
     *         currency: string,
     *         description: string,
     *         why_work_with_me: string|null,
     *         fast_service_tile: string|null,
     *         fast_service_price: float|null,
     *         fast_service_days: int|null,
     *         fast_dis: string|null,
     *         buyer: string|null,
     *         is_wishlist: bool,
     *         is_feature: bool,
     *         is_hot: bool,
     *         is_recommend: bool,
     *         is_authenticated: bool,
     *         rating: float,
     *         reviews: int,
     *         order_in_queue: int,
     *         video_platform: string|null,
     *         video_link: string|null,
     *         status: int,
     *         provider_image: string,
     *         created_at: string,
     *         gig_image: array<string>,
     *         faqs: array<array{question: string, answer: string}>,
     *         extra_service: array<array{id: int, name: string, price: float, days: int}>,
     *         recent_works: array<array{title: string, slug: string, image: string|null}>,
     *         provider_info: array{
     *             provider_image: string|null,
     *             provider_name: string,
     *             rating: float,
     *             reviews: int,
     *             location: string|null,
     *             member_since: string,
     *             speaks: string,
     *             last_project_delivery: string,
     *             avg_response_time: string,
     *             about_me: string|null
     *         }
     *     }
     * }
     */
    public function listDetailsApi(string $slug): array;

    /**
     * Update gig status
     *
     * @param int $id
     * @param int $status
     *
     * @return array{
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function updateStatus(int $id, int $status): array;

    /**
     * Get gig details by ID
     *
     * @param int $id
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     data: \App\Models\Gigs|null
     * }
     */
    public function gigDetails(int $id): array;

    /**
     * Delete a gig and its related data
     *
     * @param int $gigId
     *
     * @return array{
     *     success: bool,
     *     message: string
     * }
     */
    public function deleteGigs(int $gigId): array;
    /**
     * Get gig details by slug
     *
     * @param string $slug
     *
     * @return array{
     *     gigs: \App\Models\Gigs,
     *     category: \Modules\Category\Models\Categories|null,
     *     subCategory: \Modules\Category\Models\Categories|null,
     *     user: \App\Models\User|null,
     *     userDetails: \App\Models\UserDetail|null,
     *     tags: array<string>,
     *     extraServices: array<array{
     *         id: int,
     *         name: string,
     *         price: float,
     *         days: int
     *     }>,
     *     faqs: array<array{
     *         question: string,
     *         answer: string
     *     }>
     * }
     */
    public function gigDetailsBySlug(string $slug): array;
}
