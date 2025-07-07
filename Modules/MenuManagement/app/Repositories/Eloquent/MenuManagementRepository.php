<?php

namespace Modules\MenuManagement\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\MenuManagement\Models\Menu;
use Modules\MenuManagement\Repositories\Contracts\MenuManagementInterface;

class MenuManagementRepository implements MenuManagementInterface
{
    protected Menu $model;

    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $menu = $this->model->findOrFail($id);
        return $menu->update($data);
    }

    public function delete(int $id): bool
    {
        $menu = $this->model->findOrFail($id);
        return $menu->delete();
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function all(array $filters = []): array
    {
        $query = $this->model->query();

        if (isset($filters['language_id'])) {
            $query->where('language_id', $filters['language_id']);
        }

        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('menu_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('permenantlink', 'like', '%' . $searchTerm . '%');
            });
        }

        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
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
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get()->all();
    }

    public function exists(array $conditions): bool
    {
        return $this->model->where($conditions)->exists();
    }

    public function getPagesByLanguage(int $languageId): Collection
    {
        return DB::table('pages')
            ->select('id', 'page_title', 'slug')
            ->where('language_id', $languageId)
            ->get();
    }

    public function getMenusByLanguage(int $languageId): Collection
    {
        return $this->model->where('language_id', $languageId)
            ->select('id', 'name')
            ->get();
    }

    public function updateMenuItems(int $menuId, array $items): bool
    {
        $menu = $this->model->findOrFail($menuId);
        return $menu->update(['menus' => json_encode($items)]);
    }
}
