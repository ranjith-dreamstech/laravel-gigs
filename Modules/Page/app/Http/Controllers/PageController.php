<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Gigs;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Category\Models\Categories;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\TranslationLanguage;
use Modules\Page\Http\Requests\PageRequest;
use Modules\Page\Models\Page;
use Modules\Page\Repositories\Contracts\PageInterface;

class PageController extends Controller
{
    protected PageInterface $pageRepository;

    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\View\View
    {
        $authUser = current_user();
        $languages = Language::with('transLang')->get();
        return view('page::page.index', compact('authUser', 'languages'));
    }

    /**
     * Displays the form to add a new page.
     *
     * @return \Illuminate\View\View
     */
    public function addPage(): View
    {
        $authUser = current_user();
        return view('page::page.add.index', compact('authUser'));
    }

    public function editPage(string $slug, Request $request): View
    {
        $languageId = $request->query('language_id');
        $language = TranslationLanguage::find($languageId);

        $slugsToTry = [$slug, Str::start($slug, 'pages/')];

        $query = Page::whereIn('slug', $slugsToTry)
            ->when($languageId, fn($q) => $q->where('language_id', $languageId))
            ->first();
        if (! $query && $languageId) {
            $basePage = Page::whereIn('slug', $slugsToTry)
                ->whereNull('parent_id')
                ->first();

            if ($basePage) {
                $query = Page::where('parent_id', $basePage->id)
                    ->where('language_id', $languageId)
                    ->first();
                if (! $query) {
                    $query = new Page([
                        'language_id' => $languageId,
                        'parent_id' => $basePage->id,
                        'theme_id' => $basePage->theme_id,
                    ]);
                }
            }
        }

        if (! $query) {
            $basePage = Page::whereIn('slug', $slugsToTry)->first();

            if ($basePage) {
                $parentId = $basePage->parent_id ?? $basePage->id;
                $query = Page::where('parent_id', $parentId)
                    ->where('language_id', $languageId)
                    ->first();
                if (! $query) {
                    $query = new Page([
                        'language_id' => $languageId,
                        'parent_id' => $parentId,
                        'theme_id' => $basePage->theme_id,
                    ]);
                }
            }
        }

        if ($language) {
            app()->setLocale($language->code);
        }

        return view('page::page.edit.index', compact('query', 'languageId'));
    }

    public function getPageInfo(Request $request): JsonResponse
    {
        try {
            $pageSlug = $request->get('page_slug');
            $page = $this->pageRepository->findBySlug($pageSlug);

            if (! $page) {
                $fallbackSlug = 'pages/' . ltrim($pageSlug, '/');
                $page = $this->pageRepository->findBySlug($fallbackSlug);
            }

            if (! $page) {
                return response()->json(['exists' => 'no'], 404);
            }

            return response()->json(['exists' => 'yes']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function pageContent(Request $request): JsonResponse
    {
        $pageId = $request->page_id;

        if (! $pageId) {
            return response()->json([
                'success' => false,
                'message' => 'Page ID is required',
            ], 400);
        }

        $page = $this->pageRepository->findById($pageId);

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'page_content' => $page->page_content,
            ],
        ], 200);
    }

    public function pageStore(PageRequest $request): JsonResponse
    {
        $authUser = current_user();

        if (! $authUser) {
            return response()->json([
                'code' => 401,
                'message' => __('User is not authenticated'),
            ], 401);
        }

        if (empty($request->page_content) || count($request->page_content) === 0) {
            return response()->json([
                'code' => 422,
                'message' => __('Please add at least one section!'),
                'errors' => ['page_content' => [__('Please add at least one section!')]],
            ], 422);
        }

        $sections = $this->prepareSections($request);
        $slug = Str::slug($request->slug);

        $data = [
            'page_title' => $request->title,
            'slug' => $slug,
            'page_content' => json_encode($sections),
            'seo_tag' => $request->meta_key,
            'seo_title' => $request->mete_title,
            'seo_description' => $request->meta_description,
            'keywords' => $request->meta_key,
            'canonical_url' => $request->canonical_url,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'language_id' => $authUser->language_id ?? null,
            'status' => 1,
        ];

        try {
            $this->pageRepository->create($data);
            return response()->json([
                'code' => 200,
                'message' => __('page_create_success'),
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('Something went wrong while saving!'),
            ], 500);
        }
    }

    public function pageUpdate(PageRequest $request): JsonResponse
    {
        $sections = $this->prepareSections($request);
        $slug = Str::slug($request->slug);

        $data = [
            'page_title' => $request->title,
            'parent_id' => $request->parent_id,
            'page_content' => json_encode($sections),
            'seo_tag' => $request->meta_key,
            'seo_title' => $request->mete_title,
            'seo_description' => $request->meta_description,
            'keywords' => $request->meta_key,
            'canonical_url' => $request->canonical_url,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'status' => 1,
        ];
        if ($request->read !== 'static') {
            $data['slug'] = $slug;
        }

        if ($request->filled('language_id')) {
            $data['language_id'] = $request->language_id;
        }

        if ($request->filled('page_id')) {
            $page = $this->pageRepository->update($request->page_id, $data);
            return response()->json([
                'code' => 200,
                'message' => __('Page updated successfully'),
                'data' => $page,
            ]);
        }
        $data['language_id'] = $request->language_id;
        $page = $this->pageRepository->create($data);
        return response()->json([
            'code' => 200,
            'message' => __('Page created successfully'),
            'data' => $page,
        ]);
    }

    public function indexBuilderList(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'sort' => $request->input('sort'),
            'language_id' => $request->input('language_id') ?? $request->input('lang_id'),
        ];

        $pages = $this->pageRepository->getPagesWithFilters($filters);

        $data = [];
        foreach ($pages as $page) {
            $data[] = [
                'id' => $page->id,
                'page_title' => $page->page_title,
                'read' => $page->read,
                'slug' => $page->slug,
                'page_content' => $page->page_content,
                'status' => $page->status,
                'updated_date' => formatDateTime($page->updated_at, false),
            ];
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function pageBuilderApi(Request $request)
    {
        $defaultThemeValue = GeneralSetting::where('key', 'default_theme')->first();

        $themeId = $defaultThemeValue ? $defaultThemeValue->value : 1;

        $slug = $request->slug;

        $authUser = current_user();

        $lang_id = null;

        if ($authUser && ! empty($authUser->language_id)) {
            $lang_id = $authUser->language_id;
        } elseif (App::getLocale()) {
            $currentLocale = App::getLocale();
            $language = TranslationLanguage::where('code', $currentLocale)->first();
            $lang_id = $language->id ?? null;
        } else {
            $defaultLang = Language::select('language_id')->where('default', 1)->first();
            $lang_id = $defaultLang->language_id ?? 1;
        }

        // dd($lang_id);

        if ($themeId === '1') {
            if ($slug === null || $slug === '/') {
                $slug = 'screen-one';
            }
        } else {
            if ($slug === null || $slug === '/') {
                $slug = 'screen-two';
            }
        }

        if (! $slug) {
            return response()->json(['status' => 'error', 'message' => __('Slug must be specified')]);
        }

        $page = Page::where('slug', $slug)->where('theme_id', $themeId)->where('language_id', $lang_id)->first();

        if (! $page) {
            $basePage = Page::where('slug', $slug)->whereNull('parent_id')->first();

            if ($basePage) {
                $page = Page::where('parent_id', $basePage->id)->where('language_id', $lang_id)->where('theme_id', $themeId)->first();
            }
        }

        if (! $page) {
            return response()->json(['code' => 404, 'message' => __('Page not found.'), 'data' => []], 404);
        }

        $pageContentSections = json_decode($page->page_content, true) ?? [];

        if (empty($pageContentSections) || ! collect((array) $pageContentSections)->contains(fn($section) => $section['status'] === 1)) {
            $pageContentSections = [];
        } else {
            foreach ($pageContentSections as &$section) {
                // Banner One
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && strpos($section['section_content'], '[banner_one') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';
                        $order = $matches[3] ?? 'asc';

                        // Use provided $lang_id instead of current_user()
                        $banners = DB::table('sections')
                            ->join('section_datas', function ($join) use ($lang_id) {
                                $join->on('sections.id', '=', 'section_datas.section_id')
                                    ->where('section_datas.language_id', '=', $lang_id);
                            })
                            ->select('sections.id', 'section_datas.datas')
                            ->where('sections.name', 'Banner One')
                            ->orderBy('sections.id', $order)
                            ->limit($limit)
                            ->get();

                        foreach ($banners as &$banner) {
                            $decodedData = json_decode($banner->datas, true);

                            $banner->label = $decodedData['label_one'] ?? null;
                            $banner->line_one = $decodedData['line_one'] ?? null;
                            $banner->line_two = $decodedData['line_two'] ?? null;
                            $banner->description = $decodedData['description_one'] ?? null;
                            $banner->rating = '4.5';
                            $banner->review_count = '453';
                            $banner->average_ratings = '4.5/5';
                            $banner->trusted_customer = '4,300';

                            $banner->customer_images = [
                                asset('backend/assets/img/profiles/avatar-05.jpg'),
                                asset('backend/assets/img/profiles/avatar-12.jpg'),
                                asset('backend/assets/img/profiles/avatar-18.jpg'),
                            ];

                            $relativePath = 'storage/' . ($decodedData['thumbnail_image_one'] ?? '');

                            $banner->thumbnail_image = isset($decodedData['thumbnail_image_one']) && file_exists(public_path($relativePath))
                                ? asset($relativePath)
                                : null;

                            unset($banner->datas);
                        }

                        $section['section_type'] = 'banner';
                        $section['type'] = 'banner';
                        $section['design'] = 'banner_one';
                        $section['section_content'] = $banners;
                    }
                }

                // Category section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && strpos($section['section_content'], '[category ') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';
                        $order = $matches[3] ?? 'asc';

                        $category = Categories::select('name', 'slug', 'icon', 'id')
                            ->limit((int) $limit)
                            ->where('language_id', $lang_id)
                            ->where('status', 1)
                            ->whereNull('parent_id')
                            ->whereNull('deleted_at')
                            ->get()
                            ->map(function ($category) use ($lang_id) {
                                $category->service_count = Gigs::where('category_id', $category->id)
                                    ->where('language_id', $lang_id)
                                    ->count();
                                $category->image_url = $category->icon
                                    ? uploadedAsset(str_replace('categories/icons/', 'categories/icons/small/', ltrim($category->icon)))
                                    : asset('images/default.png');
                                return $category;
                            });
                        $section['section_type'] = 'featured_category';
                        $section['design'] = 'category_one';
                        $section['section_content'] = $category;
                    }
                }

                // FAQ Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && strpos($section['section_content'], '[faq') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $order = $matches[3] ?? 'asc';

                        $faqs = DB::table('faqs')->select('id', 'question', 'answer', 'status')->where('status', 1)
                            ->whereNull('deleted_at')
                            ->where('language_id', $lang_id)
                            ->orderBy('created_at', $order)
                            ->limit((int) $limit)
                            ->get();

                        $section['section_type'] = 'faq';
                        $section['design'] = 'faq_one';
                        $section['section_content'] = $faqs;
                    }
                }

                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && strpos($section['section_content'], '[how_it_work') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = isset($matches[1]) ? (int) $matches[1] : 10;
                        $viewAll = $matches[2] ?? 'no';
                        $order = $matches[3] ?? 'asc';

                        // Get both data sources
                        $howItWorksSettings = DB::table('general_settings')
                            ->select('id', 'key', 'value', 'group_id')
                            ->where('group_id', 10)
                            ->where('language_id', $lang_id)
                            ->orderBy('created_at', $order)
                            ->limit($limit)
                            ->get();

                        $howItWorksSections = DB::table('sections')
                            ->join('section_datas', function ($join) use ($lang_id) {
                                $join->on('sections.id', '=', 'section_datas.section_id')
                                    ->where('section_datas.language_id', '=', $lang_id);
                            })
                            ->select('sections.id', 'section_datas.datas')
                            ->where('sections.name', 'How It Work')
                            ->orderBy('sections.id', $order)
                            ->limit($limit)
                            ->get();

                        $howItWorksSections = $howItWorksSections->map(function ($item) {
                            $decoded = json_decode($item->datas, true);

                            if (is_array($decoded)) {
                                foreach ($decoded as $key => $value) {
                                    if (strpos($key, 'image_') === 0 && ! empty($value)) {
                                        $decoded[$key] = uploadedAsset($value);
                                    }
                                }

                                $item->datas = $decoded;
                            }

                            return $item;
                        });

                        // Merge both into one collection
                        $merged = $howItWorksSections->concat($howItWorksSettings);

                        // Assign to section
                        $section['section_type'] = 'how_it_works';
                        $section['design'] = 'how_it_works_one';
                        $section['section_content'] = $merged;
                    }
                }

                // Gigs Service
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && strpos($section['section_content'], '[service') !== false) {
                        preg_match('/type=([a-zA-Z]+)\s+limit=(\d+)\s+viewall=(yes|no)/', $section['section_content'], $matches);
                        $type = $matches[1] ?? 'all';
                        $viewAll = $matches[3] ?? 'no';

                        $query = Gigs::where('status', 1)->where('language_id', $lang_id);

                        if ($type === 'new') {
                            $gigs = $query->where('is_recommend', 1)->get();
                            $section['section_type'] = 'new_gigs';
                            $section['design'] = 'gigs_one';
                        } elseif ($type === 'is_feature ') {
                            $gigs = $query->where('feature', 1)->get();
                            $section['section_type'] = 'feature_gigs';
                            $section['design'] = 'gigs_two';
                        } else {
                            $gigs = $query->get();
                            $section['section_type'] = 'al_gigs';
                            $section['design'] = 'gigs_three';
                        }

                        $gigs = $query->limit(6)->get();

                        $data = [];

                        foreach ($gigs as $gig) {
                            $meta = $gig->meta()->where('key', 'gigs_image')->first();

                            $images = [];

                            if ($meta && $meta->value) {
                                $imagePaths = json_decode($meta->value, true);

                                if (is_array($imagePaths)) {
                                    foreach ($imagePaths as $imgPath) {
                                        if (is_string($imgPath)) {
                                            $mediumPath = str_replace('gigs/images/', 'gigs/images/medium/', $imgPath);
                                            $images[] = uploadedAsset($mediumPath);
                                        }
                                    }
                                }
                            }
                            $categoryName = optional($gig->category)->name ?? 'N/A';
                            $user = $gig->user;
                            $firstName = $user->userDetails->first_name ?? null;
                            $lastName = $user->userDetails->last_name ?? null;
                            $providerName = trim(($firstName ?? '') . ' ' . ($lastName ?? '')) ?: $user->name;
                            $providerImage = $user->userDetails->profile_image ?? null;
                            $is_wishlist = false;
                            if (Auth::guard('web')->check() && Wishlist::where('service_id', $gig->id)->where('user_id', Auth::guard('web')->user()->id)->exists()) {
                                $is_wishlist = true;
                            }
                            $data[] = [
                                'id' => $gig->id,
                                'title' => $gig->title,
                                'slug' => $gig->slug,
                                'general_price' => $gig->general_price,
                                'days' => $gig->days,
                                'category' => $categoryName,
                                'location' => 'Canada',
                                'category_id' => $gig->category_id,
                                'sub_category_id' => $gig->sub_category_id,
                                'no_revisions' => $gig->no_revisions,
                                'tags' => $gig->tags,
                                'description' => $gig->description,
                                'fast_service_tile' => $gig->fast_service_tile,
                                'fast_service_price' => $gig->fast_service_price,
                                'fast_service_days' => $gig->fast_service_days,
                                'fast_dis' => $gig->fast_dis,
                                'buyer' => $gig->buyer,
                                'is_wishlist' => $is_wishlist,
                                'is_feature' => (bool) $gig->is_feature,
                                'is_hot' => (bool) random_int(0, 1),
                                'is_recommend' => (bool) $gig->is_recommend,
                                'rating' => round(mt_rand(45, 50) / 10, 1),
                                'reviews' => [10, 30, 40][array_rand([10, 30, 40])],
                                'video_platform' => $gig->video_platform ?? null,
                                'video_link' => $gig->video_link ?? null,
                                'status' => $gig->status,
                                'gig_image' => $images,
                                'provider_name' => $providerName,
                                'provider_image' => $providerImage,
                            ];
                        }

                        $section['section_content'] = $data;
                    }
                }

                // Providers
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[provider') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';

                        $users = User::where('language_id', $lang_id)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->with(['userDetails.country']) // Pull userDetails and inside it country
                            ->withCount('gigs')
                            ->has('gigs')
                            ->limit((int) $limit)
                            ->get();

                        $data = [];

                        foreach ($users as $user) {
                            $data[] = [
                                'user_name' => $user->name,
                                'total_gigs' => $user->gigs_count,
                                'first_name' => $user->userDetails->first_name ?? '',
                                'last_name' => $user->userDetails->last_name ?? '',
                                'address' => $user->userDetails->address ?? '',
                                'job_title' => $user->userDetails->job_title ?? '',
                                'country' => $user->userDetails->country->name ?? '',
                                'profile_image' => $user->userDetails->profile_image ?? '',
                            ];
                        }

                        $section['section_type'] = 'provider';
                        $section['design'] = 'provider_one';
                        $section['section_content'] = $data;
                    }
                }

                //Testimonial
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[testimonial') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';

                        $testimonials = DB::table('testimonials')->select('customer_name', 'image', 'ratings', 'review', 'location')
                            ->limit((int) $limit)
                            ->where('language_id', $lang_id)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->take(2)
                            ->get();

                        foreach ($testimonials as &$testimonial) {
                            $testimonial->image = asset('storage/' . $testimonial->image);
                        }

                        $section['customer_total'] = 10;

                        $section['section_type'] = 'testimonial';
                        $section['design'] = 'testimonial_one';
                        $section['section_content'] = $testimonials;
                    }
                }

                // AD Card Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[ad_card') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';
                        $order = $matches[3] ?? 'asc';

                        $how_it_works = DB::table('general_settings')->select('key', 'value')
                            ->where(['group_id' => 15])
                            ->orderBy('created_at', $order)
                            ->limit((int) $limit)
                            ->get();

                        $section['section_type'] = 'ad_card_section';
                        $section['design'] = 'ad_card_section_one';
                        $section['section_content'] = $how_it_works;
                    }
                }

                // Why Choose Us Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[why_us') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $order = $matches[3] ?? 'asc';

                        // Instead of fetching banners, provide static content
                        $section['section_type'] = 'why_us_section';
                        $section['type'] = 'why_us_section';
                        $section['design'] = 'why_us_one';
                        $section['section_content'] = [
                            'title' => 'Why Choose Us Section',
                            'description' => 'Find the Best Gigs Easily.',
                        ];
                    }
                }

                // Search Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[search') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $viewAll = $matches[2] ?? 'no';
                        $order = $matches[3] ?? 'asc';

                        // Instead of fetching banners, provide static content
                        $section['section_type'] = 'search_section';
                        $section['type'] = 'search_section';
                        $section['design'] = 'search_one';
                        $section['section_content'] = [
                            'title' => 'Search Section',
                            'description' => 'Find the Best Gigs Easily.',
                        ];
                    }
                }

                // Marquee Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[marquee') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $order = $matches[3] ?? 'asc';

                        $page = Page::select('keywords')->where('slug', $slug)->where('language_id', $lang_id)->first();

                        if (! $page) {
                            $basePage = Page::select('keywords', 'id')->where('slug', $slug)->whereNull('parent_id')->first();

                            if ($basePage) {
                                $page = Page::select('keywords')->where('parent_id', $basePage->id)->where('language_id', $lang_id)->first();
                            }
                        }

                        $keywordsJsonArray = [];
                        if ($page && isset($page->keywords)) {
                            $keywords = array_map('trim', explode(',', $page->keywords));
                            foreach ($keywords as $keyword) {
                                $keywordsJsonArray[] = ['text' => $keyword];
                            }
                        }

                        $section['section_type'] = 'marquee_section';
                        $section['type'] = 'marquee_section';
                        $section['design'] = 'marquee_one';
                        $section['section_content'] = $keywordsJsonArray;
                    }
                }

                // Join Us Section
                if ($section['status'] === 1) {
                    if (isset($section['section_content']) && is_string($section['section_content']) && strpos($section['section_content'], '[join_us') !== false) {
                        preg_match('/limit=(\d+)\s+viewall=(yes|no)\s+order=(asc|desc)/', $section['section_content'], $matches);
                        $limit = $matches[1] ?? 10;
                        $order = $matches[3] ?? 'asc';

                        // Instead of fetching banners, provide static content
                        $section['section_type'] = 'joinus_section';
                        $section['type'] = 'joinus_section';
                        $section['design'] = 'joinus_one';
                        $section['section_content'] = [
                            'title' => 'Start as seller section',
                            'description' => 'Find the Best Gigs Easily.',
                        ];
                    }
                }

                if (isset($section['section_content']) && is_string($section['section_content'])) {
                    if (preg_match('/\[[^\]]+\]/', $section['section_content']) === 0) {
                        $section['section_type'] = 'multiple_section';
                    }
                }
            }
        }
        $languageCode = app()->getLocale();
        $language_id = getLanguageId($languageCode);
        $cookieSettings = GeneralSetting::where('group_id', 7)->where('language_id', $language_id)->pluck('value', 'key');
        $cookieResponse = [
            'content' => $cookieSettings['cookiesContentText_' . $language_id] ?? '',
            'position' => $cookieSettings['cookiesPosition_' . $language_id] ?? '',
            'agree_btn_text' => $cookieSettings['agreeButtonText_' . $language_id] ?? '',
            'decline_btn_text' => $cookieSettings['declineButtonText_' . $language_id] ?? '',
            'show_decline_btn' => $cookieSettings['showDeclineButton_' . $language_id] ?? '',
            'cookies_page_link' => $cookieSettings['cookiesPageLink_' . $language_id] ?? '',
        ];
        $page = Page::where('slug', $slug)->where('theme_id', $themeId)->where('language_id', $lang_id)->first();
        $data = [
            'page_title' => $page ? $page->page_title : '',
            'slug' => $page ? $page->slug : '',
            'currency' => getDefaultCurrencySymbol(),
            'language_id' => $page ? $page->language_id : null,
            'content_sections' => $pageContentSections,
            'seo_tag' => $page ? $page->seo_tag : '',
            'seo_title' => $page ? $page->seo_title : '',
            'seo_description' => $page ? $page->seo_description : '',
            'status' => $page ? $page->status : 0,
            'cookie_settings' => $cookieResponse,
        ];
        $seo_title = $page ? $page->seo_title : '';
        $seo_description = $page ? $page->seo_description : '';
        $og_title = $page ? $page->og_title : '';
        $og_description = $page ? $page->og_description : '';
        $meta_keywords = $page ? $page->keywords : '';
        $categories = Categories::select('id', 'name', 'image', 'icon')->where('language_id', $language_id)->where('status', 'active')->whereNull('parent_id')->get();
        $content_sections = collect((array) $data['content_sections']);
        if (request()->has('is_mobile') && request()->get('is_mobile') === 'yes') {
            return response()->json(['code' => '200', 'message' => __('Page details retrieved successfully.'), 'data' => $data], 200);
        }
        $defaultTheme = GeneralSetting::where('key', 'default_theme')->first();
        $theme = $defaultTheme ? $defaultTheme->value : 1;
        $viewFileName = 'home_' . $theme;
        return view('frontend.home.' . $viewFileName, compact('categories', 'data', 'content_sections', 'seo_title', 'seo_description', 'og_title', 'og_description', 'meta_keywords'));
    }

    public function getPage(string $slug): \Illuminate\View\View
    {
        $defaultLang = 'en';
        $language = TranslationLanguage::where('code', $defaultLang)->first();
        if (! $language) {
            abort(404);
        }
        $page = Page::where('slug', $slug)->where('language_id', $language->id)->first();
        $userLanguageCode = App::getLocale();
        $userLanguage = TranslationLanguage::where('code', $userLanguageCode)->first();

        if ($page && $userLanguage) {
            if ($userLanguage->id !== $language->id) {
                $translatedPage = Page::where('parent_id', $page->id)->where('language_id', $userLanguage->id)->first();
                if ($translatedPage) {
                    $page = $translatedPage;
                } else {
                    abort(404);
                }
            }
            $pageContent = $page && $page->page_content ? json_decode($page->page_content) : [];
            $sectionContent = $pageContent && isset($pageContent[0]->section_content) ? $pageContent[0]->section_content : [];
            $seo_title = $page ? $page->page_title : '';
            return view('frontend.pages.page', compact('page', 'sectionContent', 'seo_title'));
        }
        abort(404);

        abort(404);
    }

    public function contactUs(Request $request): \Illuminate\View\View
    {
        $seo_title = __('web.home.contact_us');
        $companyPhoneNumber = GeneralSetting::where('key', 'company_phone')->first();
        $companyEmail = GeneralSetting::where('key', 'company_email')->first();
        $companyAddress = GeneralSetting::where('key', 'company_address_line')->first();
        $companyPhoneNumber = $companyPhoneNumber ? $companyPhoneNumber->value : '';
        $companyEmail = $companyEmail ? $companyEmail->value : '';
        $companyAddress = $companyAddress ? $companyAddress->value : '';

        return view('frontend.pages.contact-us', compact('companyPhoneNumber', 'companyEmail', 'companyAddress', 'seo_title'));
    }

    public function categories(): \Illuminate\View\View
    {
        return view('frontend.pages.categories');
    }

    public function fetchCategories(Request $request): \Illuminate\Http\JsonResponse
    {
        $languageCode = App::getLocale();
        $languageId = getLanguageId($languageCode);
        $pageLength = $request->page_length ?? 12;
        $languageCode = App::getLocale();
        $languageId = getLanguageId($languageCode);
        $pageLength = $request->page_length ?? 12;

        $categories = Categories::query()
            ->where('categories.language_id', $languageId)
            ->where('categories.status', 'active')
            ->whereNull('categories.parent_id');

        if ($request->has('sortby') && $request->sortby !== null) {
            switch ($request->sortby) {
                case 'featured':
                    $categories->orderBy('categories.featured', 'desc');
                    break;

                case 'asc':
                    $categories->leftJoin('gigs', 'categories.id', '=', 'gigs.category_id')
                        ->select('categories.*', DB::raw('AVG(gigs.general_price) as avg_price'))
                        ->groupBy('categories.id')
                        ->orderBy('avg_price', 'asc');
                    break;

                case 'desc':
                    $categories->leftJoin('gigs', 'categories.id', '=', 'gigs.category_id')
                        ->select('categories.*', DB::raw('AVG(gigs.general_price) as avg_price'))
                        ->groupBy('categories.id')
                        ->orderBy('avg_price', 'desc');
                    break;
            }
        } else {
            $categories->select('categories.*');
        }

        $categories = $categories->paginate($pageLength);

        $categoryResource = CategoryResource::collection($categories);

        $pagination = [
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
            'next_page_url' => $categories->nextPageUrl(),
            'prev_page_url' => $categories->previousPageUrl(),
        ];

        $totalCategories = Categories::where('language_id', $languageId)
            ->where('status', 'active')
            ->whereNull('parent_id')
            ->count();

        $totalServices = Gigs::where('status', 1)
            ->where('language_id', $languageId)
            ->count();

        return response()->json([
            'status' => true,
            'data' => $categoryResource,
            'pagination' => $pagination,
            'totalCategories' => $totalCategories,
            'totalServices' => $totalServices,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function prepareSections(Request $request): array
    {
        $sections = [];
        $titles = $request->input('section_title', []);
        $labels = $request->input('section_label', []);
        $contents = $request->input('page_content', []);
        $statuses = $request->input('page_status', []);

        $sectionCount = count($titles);

        for ($i = 0; $i < $sectionCount; $i++) {
            $sections[] = [
                'section_title' => $titles[$i] ?? '',
                'section_label' => $labels[$i] ?? '',
                'section_content' => $contents[$i] ?? '',
                'status' => isset($statuses[$i]) ? 1 : 0,
            ];
        }

        return $sections;
    }
}
