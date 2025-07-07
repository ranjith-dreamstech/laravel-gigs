<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\ServiceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Category\Models\Categories;

class ServiceController extends Controller
{
    protected ServiceRepository $serviceRepository;

    public function __construct()
    {
        $this->serviceRepository = new ServiceRepository();
    }

    public function index(Request $request): View
    {
        $categories = $this->serviceRepository->fetchAllCategories();
        $seo_title = __('web.common.gigs');
        $seo_description = __('web.common.gigs');
        $og_title = __('web.common.gigs');
        $filterCategory = null;
        if ($request->has('c') && $request->input('c') !== null) {
            $category = Categories::where('slug', $request->input('c'))->first();
            if ($category) {
                $filterCategory = $category->id;
            }
        }
        return view('frontend.service.list', compact('categories', 'seo_title', 'seo_description', 'og_title', 'filterCategory'));
    }

    public function serviceDetail(string $slug): View
    {
        $data = $this->serviceRepository->serviceDetail($slug);
        return view('frontend.service.details', $data);
    }

    public function searchGigs(Request $request): JsonResponse
    {
        $keyword = trim($request->input('query'));
        $response = $this->serviceRepository->searchGigs($keyword);

        return response()->json([
            'status' => true,
            'data' => $response,
        ]);
    }
}
