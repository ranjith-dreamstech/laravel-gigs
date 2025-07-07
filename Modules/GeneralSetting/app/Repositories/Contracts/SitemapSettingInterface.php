<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Contracts\View\View;
use Modules\GeneralSetting\Models\SitemapUrl;

interface SitemapSettingInterface
{
    public function index(): View;

    /**
     * @param array{
     *     url: string
     * } $data
     */
    public function store(array $data): SitemapUrl;

    public function generateSitemap(): string;

    /**
     * @param array{
     *     length?: int,
     *     start?: int,
     *     keyword?: string,
     *     draw?: int
     * } $filters
     *
     * @return array{
     *     draw: int,
     *     recordsTotal: int,
     *     recordsFiltered: int,
     *     data: \Illuminate\Support\Collection<int, array{
     *         filePath: string,
     *         url: string|null,
     *         sitemap_path: string|null,
     *         id: int
     *     }>
     * }
     */
    public function getSitemapUrls(array $filters): array;

    public function deleteSitemapUrl(int $id): bool;
}
