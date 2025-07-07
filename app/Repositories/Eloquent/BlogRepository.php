<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BlogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Modules\GeneralSetting\Models\BlogCategory;
use Modules\GeneralSetting\Models\BlogPost;
use Modules\GeneralSetting\Models\BlogReviews;
use Modules\GeneralSetting\Models\BlogTag;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\TranslationLanguage;

class BlogRepository implements BlogRepositoryInterface
{
    public function fetchBlogs(Request $request): array
    {
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

        $languages = Language::with('transLang')->get();

        $query = BlogPost::join('users', 'blog_posts.created_by', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('blog_categories', 'blog_posts.category', '=', 'blog_categories.id')
            ->leftJoin('blog_tags', 'blog_posts.tags', '=', 'blog_tags.id')
            ->where('blog_posts.language_id', $lang_id)
            ->whereNull('blog_posts.deleted_at')
            ->where('blog_posts.status', 1)
            ->whereNotNull('blog_posts.slug')
            ->select('blog_posts.*', 'users.name as customer', 'user_details.profile_image', 'blog_categories.name as category', 'blog_tags.name as tag');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('blog_categories.name', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('blog_posts.title', 'like', '%' . $request->search . '%')
                    ->orWhere('blog_posts.description', 'like', '%' . $request->search . '%');
            });
        }

        $blogPosts = $query->latest()->get();

        $categories = BlogCategory::whereNull('deleted_at')->where('language_id', $lang_id)->limit(5)->get();
        $categoriesLimit = BlogCategory::whereNull('deleted_at')->where('language_id', $lang_id)->skip(5)->take(PHP_INT_MAX)->get();
        $tags = BlogTag::whereNull('deleted_at')->where('language_id', $lang_id)->get();

        $latestblogs = BlogPost::join('users', 'blog_posts.created_by', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('blog_posts.language_id', $lang_id)
            ->whereNull('blog_posts.deleted_at')
            ->where('blog_posts.status', 1)
            ->whereNotNull('blog_posts.slug')
            ->select('blog_posts.*', 'users.name as customer', 'user_details.profile_image')
            ->latest()
            ->limit(3)
            ->get();

        $seo_title = __('web.blog.blogs_title');

        $data = [
            'ajax' => false,
            'blogPosts' => $blogPosts,
            'categories' => $categories,
            'categoriesLimit' => $categoriesLimit,
            'tags' => $tags,
            'latestblogs' => $latestblogs,
            'languages' => $languages,
            'seo_title' => $seo_title,
        ];

        if ($request->ajax()) {
            $data['ajax'] = true;
            $data['html'] = View::make('frontend.blogs.partials.blogs-list', compact('blogPosts'))->render();
        }

        return $data;
    }

    public function fetchBlog(string $id): array
    {
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

        $categories = BlogCategory::whereNull('deleted_at')->where('language_id', $lang_id)->limit(5)->get();
        $categoriesLimit = BlogCategory::whereNull('deleted_at')->where('language_id', $lang_id)->skip(5)->take(PHP_INT_MAX)->get();
        $tags = BlogTag::whereNull('deleted_at')->where('language_id', $lang_id)->get();

        $latestblogs = BlogPost::join('users', 'blog_posts.created_by', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('blog_posts.language_id', $lang_id)
            ->whereNull('blog_posts.deleted_at')
            ->where('blog_posts.status', 1)
            ->whereNotNull('blog_posts.slug')
            ->select('blog_posts.*', 'users.name as customer', 'user_details.profile_image')
            ->latest()
            ->limit(3)
            ->get();

        $languages = Language::with('transLang')->get();

        $blogPost = BlogPost::join('blog_categories', 'blog_posts.category', '=', 'blog_categories.id')
            ->leftJoin('users', 'blog_posts.created_by', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('blog_tags', 'blog_posts.tags', '=', 'blog_tags.id')
            ->select('blog_posts.*', 'blog_categories.name as category', 'blog_tags.name as tag', 'users.name as customer', 'user_details.profile_image', 'user_details.profile_description')
            ->where('blog_posts.slug', $id)
            ->where('blog_posts.status', 1)
            ->first();

        if (! $blogPost) {
            return ['message' => 'Blog not found', 'code' => 404];
        }

        $blogReviews = BlogReviews::where('blog_id', $blogPost->id)->latest()->limit(5)->get();
        $countReview = $blogReviews->count();

        $otherBlogs = BlogPost::join('blog_categories', 'blog_posts.category', '=', 'blog_categories.id')
            ->leftJoin('users', 'blog_posts.created_by', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('blog_posts.language_id', $lang_id)
            ->where('blog_posts.slug', '!=', $id)
            ->where('blog_posts.status', 1)
            ->select('blog_posts.*', 'blog_categories.name as category', 'users.name as customer', 'user_details.profile_image', 'user_details.profile_description')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $seo_title = $blogPost->title;

        return [
            'ajax' => false,
            'blogPosts' => $blogPost,
            'languages' => $languages,
            'blogReviews' => $blogReviews,
            'countReview' => $countReview,
            'otherBlogs' => $otherBlogs,
            'seo_title' => $seo_title,
            'categories' => $categories,
            'tags' => $tags,
            'latestblogs' => $latestblogs,
            'categoriesLimit' => $categoriesLimit,
        ];
    }

    public function storeReview(Request $request): JsonResponse
    {
        $request->validate([
            'blog_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'required|string|max:1000',
        ]);

        BlogReviews::create([
            'blog_id' => $request->blog_id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'comments' => $request->comment,
            'created_at' => Carbon::now(),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Review added successfully!',
        ]);
    }
}
