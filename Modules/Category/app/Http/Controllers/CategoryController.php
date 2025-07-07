<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Http\Requests\SubCategoryRequest;
use Modules\Category\Repositories\Contracts\CategoryInterface;

class CategoryController extends Controller
{
    protected CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $languages = $this->categoryRepository->index();
        return view('category::category.category-index', compact('languages'));
    }
    public function store(CategoryRequest $request): JsonResponse
    {
        return $this->categoryRepository->store($request);
    }
    public function list(Request $request): JsonResponse
    {
        return $this->categoryRepository->list($request);
    }
    public function delete(Request $request): JsonResponse
    {
        return $this->categoryRepository->delete($request);
    }

    public function subCategoryIndex(): View
    {
        $categories = $this->categoryRepository->subCategoryIndex();
        return view('category::category.sub-category-index', compact('categories'));
    }

    public function subCategoryStore(Request $request): JsonResponse
    {
        return $this->categoryRepository->subCategoryStore($request);
    }

    public function subCategoryList(Request $request): JsonResponse
    {
        return $this->categoryRepository->subCategoryList($request);
    }
}
