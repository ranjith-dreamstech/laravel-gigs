<?php

namespace Modules\Gigs\Repositories\Eloquent;

use App\Models\Country;
use App\Models\Gigs;
use App\Models\GigsMeta;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Wishlist;
use App\Services\ImageResizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Category\Models\Categories;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\Gigs\Repositories\Contracts\GigsInterface;

class GigsRepository implements GigsInterface
{
    protected ImageResizer $imageResizer;

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }
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
    public function indexGigsList(Request $request): array
    {
        $status = $request->input('status');
        $pagelist = $request->input('pagelist', 9);

        /** @var \App\Models\User|null $auth */
        $auth = current_user();

        if (! $auth) {
            return [
                'code' => 401,
                'message' => 'Unauthorized',
                'data' => [],
            ];
        }

        /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Gigs> $query */
        $query = Gigs::where('user_id', $auth->id)
            ->where('status', $status);

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs> $gigs */
        $gigs = $query->take($pagelist)->get();

        /** @var array<array{id: int, slug: string, title: string, price: float, days: int, category_name: string|null, images: array<string>}> $gigsData */
        $gigsData = [];

        foreach ($gigs as $gig) {
            /** @var \Modules\Category\Models\Categories|null $category */
            $category = Categories::find($gig->category_id);
            $categoryName = $category ? $category->name : null;

            /** @var \App\Models\GigsMeta|null $gigMeta */
            $gigMeta = GigsMeta::where('gig_id', $gig->id)
                ->where('key', 'gigs_image')
                ->first();

            /** @var array<string> $images */
            $images = [];

            if ($gigMeta && $gigMeta->value) {
                /** @var array<string>|string|null $rawImages */
                $rawImages = json_decode($gigMeta->value, true) ?: $gigMeta->value;

                if (is_string($rawImages)) {
                    $rawImages = explode(',', $rawImages);
                }

                if (is_array($rawImages)) {
                    foreach ($rawImages as $img) {
                        if (is_string($img)) {
                            $mediumPath = str_replace('gigs/images/', 'gigs/images/medium/', ltrim($img, '/'));
                            $images[] = asset('storage/' . $mediumPath);
                        }
                    }
                }
            }

            $gigsData[] = [
                'id' => (int) $gig->id,
                'slug' => (string) $gig->slug,
                'title' => (string) $gig->title,
                'price' => (float) $gig->general_price,
                'days' => (int) $gig->days,
                'category_name' => $categoryName,
                'images' => $images,
            ];
        }

        return [
            'code' => 200,
            'message' => 'Gigs fetched successfully.',
            'data' => $gigsData,
        ];
    }

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
    public function list(Request $request): array
    {
        $orderBy = $request->order_by ?? 'desc';
        $search = $request->input('search');
        $status = $request->input('status');

        try {
            /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Gigs> $query */
            $query = Gigs::with(['category', 'subCategory'])
                ->orderBy('id', $orderBy);

            if (! empty($search)) {
                $query->where('title', 'LIKE', "%{$search}%");
            }

            if (! empty($status)) {
                $query->where('status', $status);
            }

            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs> $data */
            $data = $query->get();

            return [
                'code' => 200,
                'message' => __('admin.common.default_retrieve_success'),
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => __('admin.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

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
    public function getAddons(int $gigId): array
    {
        /** @var \Illuminate\Support\Collection<int, object{id: int, name: string, price: float, days: int}> $addons */
        $addons = DB::table('gigs_extra')
            ->where('gigs_id', $gigId)
            ->select('id', 'name', 'price', 'days')
            ->get();

        return [
            'data' => $addons,
        ];
    }

    /**
     * Get gig FAQs
     *
     * @param int $gigId
     *
     * @return array{
     *     data: \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs>
     * }
     */
    public function getFaq(int $gigId): array
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs> $faqs */
        $faqs = Gigs::where('id', $gigId)
            ->select('id', 'faqs')
            ->get();

        return [
            'data' => $faqs,
        ];
    }

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
    public function getImage(int $gigId): array
    {
        /** @var \App\Models\GigsMeta|null $meta */
        $meta = GigsMeta::where('gig_id', $gigId)
            ->where('key', 'gigs_image')
            ->select('id', 'key', 'value')
            ->first();

        /** @var array<string> $images */
        $images = [];

        if ($meta && $meta->value) {
            /** @var array<string>|null $imagePaths */
            $imagePaths = json_decode($meta->value, true) ?? [];

            if (is_array($imagePaths)) {
                $images = collect($imagePaths)->map(function (string $path) {
                    return asset('storage/' . str_replace('\\', '/', $path));
                })->all();
            }
        }

        return [
            'data' => [
                'id' => $meta->id ?? null,
                'key' => $meta->key ?? null,
                'value' => $images,
            ],
        ];
    }

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
    public function storeGigs(Request $request): array
    {
        try {
             /** @var \App\Models\User $user */
            $user = Auth::guard('web')->user();
            $slug = Str::slug($request->title);

            $data = [
                'title' => $request->title,
                'user_id' => $user->id ?? 1,
                'slug' => $slug,
                'general_price' => $request->general_price,
                'days' => $request->days,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'no_revisions' => $request->no_revisions,
                'tags' => $request->tags ?? null,
                'description' => $request->description,
                'fast_service_tile' => $request->fast_service_tile,
                'fast_service_price' => $request->fast_service_price,
                'fast_service_days' => $request->fast_service_days,
                'fast_dis' => $request->fast_dis,
                'buyer' => $request->buyer,
                'faqs' => $request->faqs,
                'extra_service' => $request->extra_service,
                'video_platform' => $request->video_platform,
                'video_link' => $request->video_link,
                'status' => $request->status,
            ];

            /** @var \App\Models\Gigs $gig */
            $gig = Gigs::create($data);

            /** @var array<array{title: string, price: float, days: int}>|null $extraServices */
            $extraServices = json_decode($request->extra_service, true);

            if ($extraServices) {
                foreach ($extraServices as $service) {
                    DB::table('gigs_extra')->insert([
                        'gigs_id' => $gig->id,
                        'name' => $service['title'],
                        'price' => (float) $service['price'],
                        'days' => (int) $service['days'],
                    ]);
                }
            }
            $storedImages = [];

            if ($request->hasFile('gigs_image')) {
                /** @var array<\Illuminate\Http\UploadedFile> $files */
                $files = $request->file('gigs_image');

                foreach ($files as $file) {
                    $uploadedPath = $this->imageResizer->uploadFile($file, 'gigs/images');

                    if ($uploadedPath !== null) {
                        $storedImages[] = $uploadedPath;
                    }
                }
                dd($storedImages);
                if (! empty($storedImages)) {
                    GigsMeta::create([
                        'gig_id' => $gig->id,
                        'key' => 'gigs_image',
                        'value' => json_encode($storedImages),
                    ]);
                }
            }
            
            if ($request->hasFile('gigs_video')) {
                $storedVideos = [];

                /** @var \Illuminate\Http\UploadedFile $file */
                foreach ($request->file('gigs_video') as $file) {
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('gigs', $filename, 'public');
                    $storedVideos[] = $path;
                }

                GigsMeta::create([
                    'gig_id' => $gig->id,
                    'key' => 'gigs_video',
                    'value' => json_encode($storedVideos),
                ]);
            }
            dd("Created");
            return [
                'code' => 200,
                'success' => true,
                'message' => 'Gigs information saved successfully.',
                'redirect_url' => route('seller.seller-gigs'),
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

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
    public function editGigs(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        /** @var \App\Models\Gigs $gig */
        $gig = Gigs::findOrFail($request->gig_id);
        $slug = Str::slug($request->title);

        $data = [
            'title' => $request->title,
            'user_id' => $user->id ?? 1,
            'slug' => $slug,
            'general_price' => $request->general_price,
            'days' => $request->days,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'no_revisions' => $request->no_revisions,
            'tags' => $request->tags,
            'description' => $request->description,
            'fast_service_tile' => $request->fast_service_tile,
            'fast_service_price' => $request->fast_service_price,
            'fast_service_days' => $request->fast_service_days,
            'fast_dis' => $request->fast_dis,
            'buyer' => $request->buyer,
            'faqs' => $request->faqs,
            'extra_service' => $request->extra_service,
            'video_platform' => $request->video_platform,
            'video_link' => $request->video_link,
            'status' => $request->status,
        ];

        DB::beginTransaction();

        try {
            $gig->update($data);

            DB::table('gigs_extra')->where('gigs_id', $gig->id)->delete();

            /** @var array<array{title: string, price: float, days: int}> $extraServices */
            $extraServices = json_decode($request->extra_service, true) ?? [];
            foreach ($extraServices as $service) {
                DB::table('gigs_extra')->insert([
                    'gigs_id' => $gig->id,
                    'name' => $service['title'],
                    'price' => (float) $service['price'],
                    'days' => (int) $service['days'],
                ]);
            }

            $storedImages = [];

            // 1. Get existing images
            $existingMeta = GigsMeta::where('gig_id', $gig->id)
                ->where('key', 'gigs_image')
                ->first();

            if ($existingMeta && $existingMeta->value) {
                $existingImages = json_decode($existingMeta->value, true);
                if (is_array($existingImages)) {
                    $storedImages = $existingImages;
                }
            }

            // 2. Process new uploads
            if ($request->hasFile('gigs_image')) {
                /** @var array<\Illuminate\Http\UploadedFile> $files */
                $files = $request->file('gigs_image');

                foreach ($files as $file) {
                    $uploadedPath = $this->imageResizer->uploadFile($file, 'gigs/images');

                    if ($uploadedPath !== null) {
                        $storedImages[] = $uploadedPath;
                    }
                }
            }

            // 3. Save the combined array
            if (! empty($storedImages)) {
                // If meta exists, update. Else create.
                GigsMeta::updateOrCreate(
                    ['gig_id' => $gig->id, 'key' => 'gigs_image'],
                    ['value' => json_encode($storedImages)]
                );
            }

            DB::commit();

            return [
                'code' => 200,
                'success' => true,
                'message' => 'Gigs information updated successfully.',
                'redirect_url' => route('seller.seller-gigs'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'code' => 500,
                'success' => false,
                'message' => 'Failed to update gig. Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get subcategories for a category
     *
     * @param int $categoryId
     *
     * @return array<array{id: int, name: string}>
     */
    public function getSubCategories(int $categoryId): array
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, \Modules\Category\Models\Categories> $subCategories */
        $subCategories = Categories::where('parent_id', $categoryId)
            ->get(['id', 'name']);

        return $subCategories->toArray();
    }

    /**
     * Get recent gigs list for API
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
     *     sub_categories?: array<int, array{id: int, name: string, service_count: int}>,
     *     services_count?: int
     * }
     */
    public function recentListApi(Request $request): array
    {
        return $this->getGigsListData($request, false);
    }

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
    public function listApi(Request $request): array
    {
        return $this->getGigsListData($request, true);
    }

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
    public function listDetailsApi(string $slug): array
    {
        if (! $slug) {
            return ['code' => 400, 'success' => false, 'message' => 'slug is required'];
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs> $gigs */
        $gigs = Gigs::withCount('reviews')
            ->withAvg('reviews', 'ratings')
            ->where('slug', $slug)
            ->get();

        if ($gigs->isEmpty()) {
            return ['code' => 404, 'success' => false, 'message' => 'Gig not found'];
        }

        $gig = $gigs->first();

        // Get currency info
        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        $currency = $currencyId ? Currency::where('id', $currencyId->value)->first() : null;
        $currencySymbol = $currency ? $currency->symbol : '$';

        // Get user info
        $user = $gig->user;
        $userDetails = $user ? $user->userDetails : null;

        // Process images
        $images = [];
        $meta = $gig->meta()->where('key', 'gigs_image')->first();
        if ($meta && $meta->value) {
            $imagePaths = json_decode($meta->value, true);

            if (is_array($imagePaths)) {
                foreach ($imagePaths as $imgPath) {
                    if (is_string($imgPath)) {
                        $mediumPath = str_replace('gigs/images/', 'gigs/images/medium/', $imgPath);
                        $images[] = asset('storage/' . $mediumPath);
                    }
                }
            }
        }

        // Process wishlist status
        $is_wishlist = false;
        if (Auth::guard('web')->check()) {
            $authUser = Auth::guard('web')->user();
            if ($authUser) {
                $is_wishlist = Wishlist::where('service_id', $gig->id)
                    ->where('user_id', $authUser->id)
                    ->exists();
            }
        }

        // Process provider info
        $firstName = $userDetails ? $userDetails->first_name : null;
        $lastName = $userDetails ? $userDetails->last_name : null;
        $fullName = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));
        if (!empty($fullName)) {
            $providerName = $fullName;
        } elseif ($user && !empty($user->name)) {
            $providerName = $user->name;
        } else {
            $providerName = 'Unknown';
        }
        $providerImage = $userDetails ? $userDetails->profile_image : null;
        $whyWithUs = $userDetails ? $userDetails->profile_description : null;
        $address = $userDetails ? $userDetails->address : null;
        $aboutMe = $userDetails ? $userDetails->about : null;
        $faqs = [];
        if (!empty($gig->faqs)) {
            $decoded = json_decode($gig->faqs, true);
            $faqs = is_array($decoded) ? $decoded : [];
        }

        $extraService = [];
        if (!empty($gig->extra_service)) {
            $decodedExtra = json_decode($gig->extra_service, true);
            $extraService = is_array($decodedExtra) ? $decodedExtra : [];
        }

        // Build response data
        $data = [
            'id' => (int) $gig->id,
            'user_id' => (int) $gig->user_id,
            'slug' => (string) $gig->slug,
            'title' => (string) ($gig->title ?? ''),
            'general_price' => (float) ($gig->general_price ?? 0),
            'days' => (int) ($gig->days ?? 0),
            'category' => (string) (optional($gig->category)->name ?? 'N/A'),
            'location' => 'Canada',
            'category_id' => (int) ($gig->category_id ?? 0),
            'sub_category_id' => (int) ($gig->sub_category_id ?? 0),
            'no_revisions' => (int) ($gig->no_revisions ?? 0),
            'tags' => $gig->tags,
            'currency' => $currencySymbol,
            'description' => (string) ($gig->description ?? ''),
            'why_work_with_me' => $whyWithUs,
            'fast_service_tile' => $gig->fast_service_tile,
            'fast_service_price' => $gig->fast_service_price ? (float) $gig->fast_service_price : null,
            'fast_service_days' => $gig->fast_service_days ? (int) $gig->fast_service_days : null,
            'fast_dis' => $gig->fast_dis,
            'buyer' => $gig->buyer,
            'is_wishlist' => $is_wishlist,
            'is_feature' => (bool) ($gig->is_featured ?? false),
            'is_hot' => (bool) random_int(0, 1),
            'is_recommend' => (bool) ($gig->is_recommended ?? false),
            'is_authenticated' => Auth::guard('web')->check(),
            'rating' => round($gig->reviews_avg_ratings ?? 0, 1),
            'reviews' => (int) ($gig->reviews_count ?? 0),
            'order_in_queue' => (int) [10, 15, 20][array_rand([10, 30, 40])],
            'video_platform' => $gig->video_platform,
            'video_link' => $gig->video_link,
            'status' => (int) ($gig->status ?? 0),
            'provider_image' => $providerImage ? $providerImage : 'https://www.w3schools.com/howto/img_avatar.png',
            'created_at' => \Carbon\Carbon::parse($gig->created_at)->format('d M Y'),
            'gig_image' => $images,
            'faqs' => $faqs,
            'extra_service' => $extraService,
            'recent_works' => Gigs::where('user_id', $gig->user_id)
                ->where('id', '!=', $gig->id)
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($work) {
                    $meta = $work->meta()->where('key', 'gigs_image')->first();
                    $images = [];
                    if ($meta && $meta->value) {
                        $imagePaths = json_decode($meta->value, true);
                        if (is_array($imagePaths) && count($imagePaths)) {
                            $images[] = asset('storage/' . $imagePaths[0]);
                        }
                    }

                    return [
                        'title' => (string) ($work->title ?? ''),
                        'slug' => (string) ($work->slug ?? ''),
                        'image' => $images[0] ?? null,
                    ];
                })->toArray(),
            'provider_info' => [
                'provider_image' => $providerImage,
                'provider_name' => (string) $providerName,
                'rating' => round(mt_rand(45, 50) / 10, 1),
                'reviews' => (int) [10, 30, 40][array_rand([10, 30, 40])],
                'location' => $address,
                'member_since' => '25 Jan 2024',
                'speaks' => 'English, Portuguese',
                'last_project_delivery' => '29 Jan 2024',
                'avg_response_time' => 'About 8 hours',
                'about_me' => $aboutMe,
            ],
        ];

        return ['code' => 200, 'success' => true, 'data' => $data];
    }

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
    public function updateStatus(int $id, int $status): array
    {
        try {
            /** @var \App\Models\Gigs|null $order */
            $order = Gigs::find($id);

            if (! $order) {
                return [
                    'code' => 404,
                    'message' => 'Gig not found',
                ];
            }

            $order->status = $status;
            $order->save();

            return [
                'code' => 200,
                'message' => 'Gig status updated successfully',
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => 'Failed to update gig status',
                'error' => $e->getMessage(),
            ];
        }
    }

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
    public function gigDetails(int $id): array
    {
        /** @var \App\Models\Gigs|null $gigs */
        $gigs = Gigs::find($id);

        return [
            'status' => 'success',
            'code' => 200,
            'data' => $gigs,
        ];
    }

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
    public function gigDetailsBySlug(string $slug): array
    {
        /** @var \App\Models\Gigs $gigs */
        $gigs = Gigs::where('slug', $slug)->firstOrFail();

        /** @var \Modules\Category\Models\Categories|null $category */
        $category = Categories::find($gigs->category_id);

        /** @var \Modules\Category\Models\Categories|null $subCategory */
        $subCategory = Categories::find($gigs->sub_category_id);

        /** @var \App\Models\User|null $user */
        $user = User::find($gigs->user_id);

        /** @var \App\Models\UserDetail|null $userDetails */
        $userDetails = UserDetail::where('user_id', $gigs->user_id)->first();

        /** @var array<string> $tags */
        $tags = [];

        if (!empty($gigs->tags)) {
            $decodedTags = json_decode($gigs->tags, true);
            if (is_array($decodedTags)) {
                $tags = $decodedTags;
            } else {
                $tags = (array) $gigs->tags;
            }
        }

        /** @var array<array{id: int, name: string, price: float, days: int}> $extraServices */
        $extraServices = $gigs->extra_service ?
            is_array(json_decode($gigs->extra_service, true)) ?
            json_decode($gigs->extra_service, true) :
            []
            : [];

        /** @var array<array{question: string, answer: string}> $faqs */
        $faqs = [];

        if (!empty($gigs->faqs)) {
            $decodedFaqs = json_decode($gigs->faqs, true);
            if (is_array($decodedFaqs)) {
                $faqs = $decodedFaqs;
            }
        }

        return [
            'gigs' => $gigs,
            'category' => $category,
            'subCategory' => $subCategory,
            'user' => $user,
            'userDetails' => $userDetails,
            'tags' => $tags,
            'extraServices' => $extraServices,
            'faqs' => $faqs,
        ];
    }

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
    public function deleteGigs(int $gigId): array
    {
        /** @var \App\Models\Gigs|null $gig */
        $gig = Gigs::find($gigId);

        if (! $gig) {
            return [
                'success' => false,
                'message' => 'Gig not found',
            ];
        }

        DB::beginTransaction();
        try {
            DB::table('gigs_extra')->where('gigs_id', $gig->id)->delete();
            GigsMeta::where('gig_id', $gig->id)->delete();
            $gig->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Gig deleted successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Error deleting gig: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get gigs list data
     *
     * @param Request $request
     * @param bool $paginate
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
     *     sub_categories?: array<int, array{id: int, name: string, service_count: int}>,
     *     services_count?: int,
     *     pagination?: array{
     *         total: int,
     *         per_page: int,
     *         current_page: int,
     *         last_page: int,
     *         from: int|null,
     *         to: int|null,
     *         next_page_url: string|null,
     *         prev_page_url: string|null
     *     }
     * }
     */
    private function getGigsListData(Request $request, bool $paginate): array
    {
        $sortBy = $request->sort_by;
        $categoryFilter = $request->filter_category;
        $perPage = $request->input('paginate', 12);
        $underBudget = $request->under_budget;
        $uptoDeliveryDays = $request->upto_delivery_days;
        $review = $request->review;
        $q = $request->q;

        /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Gigs> $query */
        $query = Gigs::withCount('reviews')
            ->withAvg('reviews', 'ratings')
            ->where('status', 1);

        $totalServiceCount = Gigs::where('status', 1)->count();

        if (! empty($q)) {
            $query->where('title', 'like', '%' . $q . '%');
        }
        if (! empty($underBudget)) {
            $query->where('general_price', '<=', $underBudget);
        }

        if (! empty($uptoDeliveryDays)) {
            $query->where('days', '<=', $uptoDeliveryDays);
        }

        if (! empty($review) && is_array($review)) {
            $ratings = implode(',', array_map('intval', $review));
            $query->havingRaw("ROUND(reviews_avg_ratings, 0) IN ({$ratings})");
        }

        if (! empty($categoryFilter) && (! $request->has('subcategory_id') || $request->subcategory_id === null)) {
            $query->where('category_id', $categoryFilter);
            $totalServiceCount = $query->count();
        }

        if ($request->has('filter_category') && $request->has('subcategory_id') && $request->filter_category !== null && $request->subcategory_id !== null) {
            $query->where('category_id', $request->filter_category)
                ->where('sub_category_id', $request->subcategory_id);
            $totalServiceCount = $query->count();
        }

        /** @var \Modules\Category\Models\Categories|null $category */
        $category = Categories::find($categoryFilter);

        /** @var array<int, array{id: int, name: string, service_count: int}> $subCategories */
        $subCategories = [];

        if ($category) {
            $subCategories = Categories::where('parent_id', $category->id)
                ->get(['id', 'name'])
                ->map(function ($subCategory) use ($category) {
                    $serviceCount = Gigs::where('category_id', $category->id)
                        ->where('sub_category_id', $subCategory->id)
                        ->count();
                    return [
                        'id' => $subCategory->id,
                        'name' => $subCategory->name,
                        'service_count' => $serviceCount,
                    ];
                })->all();
        }

        switch ($sortBy) {
            case 'is_recommend':
                $query->where('is_recommend', 1);
                break;

            case 'is_feature':
                $query->where('is_feature', 1);
                break;

            case 'high_to_low':
                $query->orderBy('general_price', 'desc');
                break;

            case 'low_to_high':
                $query->orderBy('general_price', 'asc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\Gigs>|\Illuminate\Database\Eloquent\Collection<int, \App\Models\Gigs> $gigs */
        $gigs = $paginate ? $query->paginate($perPage) : $query->get();

        $data = [];

        foreach ($gigs as $gig) {
            /** @var \App\Models\GigsMeta|null $meta */
            $meta = $gig->meta()->where('key', 'gigs_image')->first();

            $images = [];

            if ($meta && $meta->value) {
                /** @var array<string>|null $imagePaths */
                $imagePaths = json_decode($meta->value, true);

                if (is_array($imagePaths)) {
                    $images = array_filter(array_map(function ($imgPath) {
                        if (! is_string($imgPath)) {
                            return null;
                        }

                        $mediumPath = str_replace('gigs/images/', 'gigs/images/medium/', $imgPath);

                        return asset('storage/' . $mediumPath);
                    }, $imagePaths));
                }
            }

            $categoryName = optional($gig->category)->name ?? 'N/A';

            /** @var \Modules\GeneralSetting\Models\GeneralSetting|null $currencyId */
            $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
            /** @var \Modules\GeneralSetting\Models\Currency|null $currency */
            $currency = $currencyId ? Currency::where('id', $currencyId->value)->first() : null;
            $currencySymbol = $currency ? $currency->symbol : '$';

            /** @var \App\Models\User|null $user */
            $user = $gig->user;
            /** @var \App\Models\UserDetail|null $userDetails */
            $userDetails = $user ? $user->userDetails : null;

            $firstName = $userDetails ? $userDetails->first_name : null;
            $lastName = $userDetails ? $userDetails->last_name : null;

            $fullName = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));

            if (!empty($fullName)) {
                $providerName = $fullName;
            } elseif ($user && !empty($user->name)) {
                $providerName = $user->name;
            } else {
                $providerName = 'Unknown';
            }
            $providerImage = $userDetails ? $userDetails->profile_image : null;

            $state = $userDetails && $userDetails->state_id ? State::find($userDetails->state_id) : null;
            $country = $userDetails && $userDetails->country_id ? Country::find($userDetails->country_id) : null;
            $providerLocation = trim(implode(', ', array_filter([
                $state ? $state->name : null,
                $country ? $country->name : null,
            ])), ', ');

            $is_wishlist = false;
            if (Auth::guard('web')->check()) {
                /** @var \App\Models\User $authUser */
                $authUser = Auth::guard('web')->user();
                $is_wishlist = Wishlist::where('service_id', $gig->id)
                    ->where('user_id', $authUser->id)
                    ->exists();
            }

            $data[] = [
                'id' => (int) $gig->id,
                'title' => (string) ($gig->title ?? ''),
                'slug' => (string) ($gig->slug ?? ''),
                'general_price' => (float) ($gig->general_price ?? 0),
                'days' => (int) ($gig->days ?? 0),
                'category' => (string) $categoryName,
                'location' => 'Canada',
                'category_id' => (int) ($gig->category_id ?? 0),
                'sub_category_id' => (int) ($gig->sub_category_id ?? 0),
                'no_revisions' => (int) ($gig->no_revisions ?? 0),
                'tags' => $gig->tags,
                'currency' => $currencySymbol,
                'description' => (string) ($gig->description ?? ''),
                'fast_service_tile' => $gig->fast_service_tile,
                'fast_service_price' => $gig->fast_service_price ? (float) $gig->fast_service_price : null,
                'fast_service_days' => $gig->fast_service_days ? (int) $gig->fast_service_days : null,
                'fast_dis' => $gig->fast_dis,
                'buyer' => $gig->buyer,
                'is_wishlist' => $is_wishlist,
                'is_feature' => (bool) ($gig->is_featured ?? false),
                'is_hot' => (bool) random_int(0, 1),
                'is_recommend' => (bool) ($gig->is_recommended ?? false),
                'is_authenticated' => Auth::guard('web')->check(),
                'rating' => round($gig->reviews_avg_ratings ?? 0, 1),
                'reviews' => (int) ($gig->reviews_count ?? 0),
                'video_platform' => $gig->video_platform,
                'video_link' => $gig->video_link,
                'status' => (int) ($gig->status ?? 0),
                'gig_image' => $images,
                'provider_name' => $providerName,
                'provider_image' => $providerImage,
                'provider_location' => $providerLocation,
            ];
        }

        $response = [
            'status' => true,
            'message' => 'Gigs fetched successfully',
            'data' => $data,
            'category' => $category,
            'sub_categories' => $subCategories,
            'services_count' => $totalServiceCount,
        ];

        if ($paginate && $gigs instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $response['pagination'] = [
                'total' => $gigs->total(),
                'per_page' => $gigs->perPage(),
                'current_page' => $gigs->currentPage(),
                'last_page' => $gigs->lastPage(),
                'from' => $gigs->firstItem(),
                'to' => $gigs->lastItem(),
                'next_page_url' => $gigs->nextPageUrl(),
                'prev_page_url' => $gigs->previousPageUrl(),
            ];
        }

        return $response;
    }
}
