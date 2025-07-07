<?php

namespace Modules\Category\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Category\Models\Categories;
use Modules\GeneralSetting\Models\Language;

interface CategoryInterface
{
    /**
     * @return Collection<int, Language>
     */
    public function index(): Collection;
    public function store(Request $request): JsonResponse;
    public function list(Request $request): JsonResponse;
    public function delete(Request $request): JsonResponse;
    /**
     * @return Collection<int, Categories>
     */
    public function subCategoryIndex(): Collection;
    public function subCategoryStore(Request $request): JsonResponse;
    public function subCategoryList(Request $request): JsonResponse;
}
