<?php

namespace Modules\Page\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Page\Models\Page;
use Modules\Page\Repositories\Contracts\PageInterface;

/**
 * PageRepository implementation.
 */
class PageRepository implements PageInterface
{
    /**
     * @return Collection<int, Page>
     */
    public function index(): Collection
    {
        return Page::all();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): bool
    {
        return (bool) Page::create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $page = Page::findOrFail($id);
        return $page->update($data);
    }

    public function delete(int $id): bool
    {
        return Page::destroy($id) > 0;
    }

    public function findBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    public function findById(int $id): Page
    {
        return Page::findOrFail($id);
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return LengthAwarePaginator<int, Page>
     */
    public function getPagesWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Page::query();

        if (! empty($filters['search'])) {
            $query->where('page_title', 'LIKE', "%{$filters['search']}%");
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['language_id']) && ! empty($filters['language_id'])) {
            $query->where('language_id', $filters['language_id']);
        }

        if (isset($filters['sort']) && $filters['sort'] === 'asc') {
            $query->orderBy('created_at', 'asc');
        } elseif (isset($filters['sort']) && $filters['sort'] === 'desc') {
            $query->orderBy('created_at', 'desc');
        } elseif (isset($filters['sort']) && $filters['sort'] === 'last_month') {
            $query->whereBetween('created_at', [now()->subMonth(), now()]);
        } elseif (isset($filters['sort']) && $filters['sort'] === 'last_7_days') {
            $query->whereBetween('created_at', [now()->subDays(7), now()]);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getTranslatedPage(int $parentId, int $languageId): ?Page
    {
        return Page::where('parent_id', $parentId)
            ->where('language_id', $languageId)
            ->first();
    }

    /**
     * @param array<string, mixed> $request
     *
     * @return mixed
     */
    public function pageBuilderApi(array $request)
    {
    }
}
