<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\BlogRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected BlogRepository $blogRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository();
    }

    public function blogList(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $data = $this->blogRepository->fetchBlogs($request);

        if ($data['ajax'] ?? false) {
            $blogPosts = $data['blogPosts'];
            return response()->json([
                'html' => view('frontend.blogs.partials.blogs-list', compact('blogPosts'))->render(),
            ]);
        }

        return view('frontend.blogs.blog-list', $data);
    }

    public function blogDetail(string $slug): View
    {
        $data = $this->blogRepository->fetchBlog($slug);
        return view('frontend.blogs.blog-details', $data);
    }

    public function storeReview(Request $request): RedirectResponse
    {
        $this->blogRepository->storeReview($request);
        return redirect()->back()->with('success', 'Review Added successfully.');
    }
}
