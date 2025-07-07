<?php

namespace Modules\Category\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Category\Models\Categories;
use Modules\Category\Repositories\Contracts\CategoryInterface;
use Modules\GeneralSetting\Models\Language;
use App\Services\ImageResizer;

class CategoryRepository implements CategoryInterface
{
    protected ImageResizer $imageResizer;

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }
    /**
     * @return Collection<int, Language>
     */
    public function index(): Collection
    {
        return Language::with('transLang')->get();
    }

    public function store(Request $request): JsonResponse
    {
        $isUpdate = $request->has('id') && ! empty($request->id);
        $categoryId = $request->id;

        /** @var Categories|null $category */
        $category = $isUpdate ? Categories::find($categoryId) : new Categories();

        if (! $category) {
            return response()->json([
                'code' => 404,
                'message' => __('admin.manage.category_not_found'),
            ]);
        }

        $category->name = $request->categoryname;
        $category->slug = Str::slug($request->slug);
        $category->description = $request->description;
        $category->language_id = $request->language_id;
        $category->featured = in_array($request->feature, ['on', '1', 'true']) ? 1 : 0;
        $category->status = in_array($request->status, ['on', '1', 'true']) ? 'active' : 'inactive';

        if ($request->hasFile('image')) {
            $uploadedImage = $this->imageResizer->uploadFile($request->file('image'), 'categories/images');
            $category->image = $uploadedImage ?? null;
        }

        if ($request->hasFile('icon')) {
            $uploadedIcon = $this->imageResizer->uploadFile($request->file('icon'), 'categories/icons');
            $category->icon = $uploadedIcon ?? null;
        }

        $category->save();

        return response()->json([
            'code' => 200,
            'message' => $isUpdate ? __('admin.manage.category_updated_successfully') : __('admin.manage.category_created_successfully'),
            'data' => $category,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $query = Categories::select('id', 'name as categoryname', 'slug', 'description', 'image', 'icon', 'status', 'language_id', 'featured as feature', 'created_at')
                ->whereNull('parent_id');

            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('language_id') && $request->language_id) {
                $query->where('language_id', $request->language_id);
            }

            if ($request->has('sort_by')) {
                switch ($request->sort_by) {
                    case 'ascending':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'descending':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'last month':
                        $query->where('created_at', '>=', now()->subMonth());
                        break;
                    case 'last 7 days':
                        $query->where('created_at', '>=', now()->subDays(7));
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
            /** @var \Yajra\DataTables\DataTables $dataTables */
            $dataTables = datatables();
            return $dataTables->eloquent($query)->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.common.something_went_wrong'),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        if (! $id) {
            return response()->json(['code' => 400, 'message' => 'Categories ID is required.'], 400);
        }

        try {
            /** @var \Modules\Category\Models\Categories $category */
            $category = Categories::findOrFail($id);
            $category->delete();

            return response()->json([
                'code' => 200,
                'message' => __('admin.manage.category_delete'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.manage.category_delete_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return Collection<int, Categories>
     */
    public function subCategoryIndex(): Collection
    {
        return Categories::whereNull('parent_id')->get();
    }

    public function subCategoryStore(Request $request): JsonResponse
    {
        $isUpdate = $request->has('id') && ! empty($request->id);
        $subCategoryId = $request->id;
        /** @var Categories|null $subCategory */
        $subCategory = $isUpdate ? Categories::find($subCategoryId) : new Categories();

        if (($isUpdate && ! $subCategory) || ! $subCategory) {
            return response()->json([
                'code' => 404,
                'message' => __('admin.manage.category_not_found'),
            ]);
        }

        $subCategory->name = $request->categoryname;
        $subCategory->slug = Str::slug($request->slug);
        $subCategory->description = $request->description;
        $subCategory->featured = in_array($request->feature, ['on', '1', 'true']) ? 1 : 0;
        $subCategory->status = in_array($request->status, ['on', '1', 'true']) ? 'active' : 'inactive';
        $subCategory->parent_id = $request->category_id;

        if ($request->hasFile('image')) {
            $uploadedImage = $this->imageResizer->uploadFile($request->file('image'), 'categories/images');
            $subCategory->image = $uploadedImage ?? null;
        }

        if ($request->hasFile('icon')) {
            $uploadedIcon = $this->imageResizer->uploadFile($request->file('icon'), 'categories/icons');
            $subCategory->icon = $uploadedIcon ?? null;
        }


        $subCategory->save();

        return response()->json([
            'code' => 200,
            'message' => $isUpdate
                ? __('admin.manage.sub_category_updated_successfully')
                : __('admin.manage.sub_category_created_successfully'),
            'data' => $subCategory,
        ]);
    }

    public function subCategoryList(Request $request): JsonResponse
    {
        try {
            $query = Categories::with('parentCategory')->whereNotNull('parent_id');

            $recordsTotal = $query->count();

            if ($request->has('search') && ! empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('parent_id', $request->category);
            }

            if ($request->filled('sort_by')) {
                switch ($request->sort_by) {
                    case 'ascending':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'descending':
                        $query->orderBy('name', 'desc');
                        break;
                    case 'last month':
                        $query->whereBetween('created_at', [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth(),
                        ])->orderBy('created_at', 'desc');
                        break;
                    case 'last 7 days':
                        $query->whereBetween('created_at', [now()->subDays(7), now()])
                            ->orderBy('created_at', 'desc');
                        break;
                    default:
                        $query->latest();
                }
            } elseif ($request->has('order')) {
                $orderColumnIndex = $request->input('order.0.column');
                $orderColumnName = $request->input("columns.{$orderColumnIndex}.data");
                $orderDirection = $request->input('order.0.dir', 'desc');

                $sortable = ['name', 'slug', 'created_at'];
                if (in_array($orderColumnName, $sortable)) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            } else {
                $query->latest();
            }

            $recordsFiltered = $query->count();

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $subCategories = $query->skip($start)->take($length)->get();

            $data = $subCategories->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                    'description' => $sub->description,
                    'featured' => $sub->featured,
                    'status' => $sub->status,
                    'image' => $sub->image,
                    'icon' => $sub->icon,
                    'parent_id' => $sub->parent_id,
                    'parent_name' => optional($sub->parentCategory)->name,
                    'created_at' => $sub->created_at ? $sub->created_at->format('Y-m-d') : null,
                ];
            });

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
