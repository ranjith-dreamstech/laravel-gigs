<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\GeneralSetting\Models\BlogCategory;
use Modules\GeneralSetting\Models\BlogPost;
use Modules\GeneralSetting\Models\BlogReviews;
use Modules\GeneralSetting\Models\BlogTag;
use Modules\GeneralSetting\Models\Language;

interface BlogCategoryRepositoryInterface
{
    /**
     * @return array{languages: Collection<int, Language>, categories: Collection<int, BlogCategory>}
     */
    public function blogCategory(): array;

    public function categoryStore(Request $request): JsonResponse;

    public function categoryUpdate(Request $request, int $id): RedirectResponse;

    /**
     * @return array{languages: Collection<int, Language>, tags: Collection<int, BlogTag>}
     */
    public function blogTags(): array;

    public function tagStore(Request $request): JsonResponse;

    public function tagUpdate(Request $request, int $id): RedirectResponse;

    public function categoryDestroy(int $id): RedirectResponse;

    public function tagDestroy(int $id): RedirectResponse;

    /**
     * @return array{comments: Collection<int, BlogReviews>}
     */
    public function blogComments(): array;

    /**
     * @return array{
     *     blogPosts: Collection<int, BlogPost>,
     *     languages: Collection<int, Language>,
     *     categories: Collection<int, BlogCategory>,
     *     tags: Collection<int, BlogTag>
     * }
     */
    public function blogs(): array;

    /**
     * @return array{blogPosts: BlogPost|null, languages: Collection<int, Language>}
     */
    public function blogDetails(string $id): array;

    /**
     * @return array{
     *     tags: Collection<int, BlogTag>,
     *     languages: Collection<int, Language>,
     *     categories: Collection<int, BlogCategory>
     * }
     */
    public function blogAdd(): array;

    public function blogStore(Request $request): JsonResponse;

    public function blogDestroy(int $id): JsonResponse;

    /**
     * @return array{
     *     blog: BlogPost,
     *     tags: Collection<int, BlogTag>,
     *     languages: Collection<int, Language>,
     *     categories: Collection<int, BlogCategory>
     * }
     */
    public function blogEdit(int $id): array;

    public function blogUpdate(Request $request, int $id): JsonResponse;
}
