<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Modules\GeneralSetting\Models\SitemapUrl;
use Modules\GeneralSetting\Repositories\Contracts\SitemapSettingInterface;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapSettingRepository implements SitemapSettingInterface
{
    public function index(): View
    {
        return view('generalsetting::other_settings.sitemap');
    }

    /**
     * @param array{
     *     url: string
     * } $data
     */
    public function store(array $data): SitemapUrl
    {
        $sitemap = new SitemapUrl();
        $sitemap->url = $data['url'];
        $sitemap->save();
        $this->generateSitemap();

        return $sitemap;
    }

    public function generateSitemap(): string
    {
        $result = '';

        try {
            $urls = SitemapUrl::all();
            if ($urls->isEmpty()) {
                return '';
            }

            if (! $this->ensureSitemapFolderExists()) {
                return '';
            }

            $sitemap = Sitemap::create();
            $this->addUrlsToSitemap($urls, $sitemap);

            $this->archivePreviousSitemap();

            $relativePath = 'sitemaps/sitemap.xml';
            $fullPath = public_path($relativePath);
            $sitemap->writeToFile($fullPath);

            if (File::exists($fullPath)) {
                $this->updateLatestUrlPath($relativePath);
                $result = $relativePath;
            }
        } catch (\Throwable $e) {
            // Log the exception if necessary
        }

        return $result;
    }


    private function addUrlsToSitemap($urls, Sitemap $sitemap): void
    {
        foreach ($urls as $url) {
            $urlString = $url->url;
            if (! empty($urlString)) {
                $sitemap->add(
                    Url::create($urlString)
                        ->setLastModificationDate(now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8)
                );
            }
        }
    }

    private function ensureSitemapFolderExists(): bool
    {
        $sitemapFolder = public_path('sitemaps');

        return File::exists($sitemapFolder) || File::makeDirectory($sitemapFolder, 0777, true) || File::isDirectory($sitemapFolder);
    }

    private function archivePreviousSitemap(): void
    {
        $lastBeforeSitemap = SitemapUrl::orderByDesc('id')->skip(1)->first();

        if (! $lastBeforeSitemap || ! $lastBeforeSitemap->sitemap_path) {
            return;
        }

        $oldPath = public_path($lastBeforeSitemap->sitemap_path);
        if (! File::exists($oldPath)) {
            return;
        }

        $newFilename = 'sitemaps/sitemap-' . date('Y-m-d-H-i-s') . '-' . random_int(1000, 9999) . '.xml';
        $newFullPath = public_path($newFilename);

        if (File::move($oldPath, $newFullPath)) {
            $lastBeforeSitemap->sitemap_path = $newFilename;
            $lastBeforeSitemap->save();
        }
    }

    private function updateLatestUrlPath(string $relativePath): void
    {
        $latestUrl = SitemapUrl::orderByDesc('id')->first();

        if ($latestUrl) {
            $latestUrl->update(['sitemap_path' => $relativePath]);
        }
    }

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
    public function getSitemapUrls(array $filters): array
    {
        $pageLength = $filters['length'] ?? 10;
        $offset = $filters['start'] ?? 0;

        $sitemapUrlsQuery = SitemapUrl::query();

        if (! empty($filters['keyword'])) {
            $sitemapUrlsQuery->where('url', 'like', '%' . $filters['keyword'] . '%');
        }

        $filteredRecords = $sitemapUrlsQuery->count();
        $totalRecords = SitemapUrl::count();

        /** @var \Illuminate\Support\Collection<int, array{filePath: string, url: string|null, sitemap_path: string|null, id: int}> $sitemapUrls */
        $sitemapUrls = $sitemapUrlsQuery->orderBy('id', 'desc')
            ->skip($offset)
            ->take($pageLength)
            ->get()
            ->map(function (SitemapUrl $sitemapUrl) {
                return [
                    'filePath' => ! empty($sitemapUrl->sitemap_path) && File::exists(public_path($sitemapUrl->sitemap_path))
                        ? asset($sitemapUrl->sitemap_path)
                        : '',
                    'url' => $sitemapUrl->url,
                    'sitemap_path' => $sitemapUrl->sitemap_path,
                    'id' => $sitemapUrl->id,
                ];
            });

        return [
            'draw' => $filters['draw'] ?? 0,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $sitemapUrls,
        ];
    }

    public function deleteSitemapUrl(int $id): bool
    {
        $sitemapUrl = SitemapUrl::findOrFail($id);

        if (! empty($sitemapUrl->sitemap_path) && File::exists(public_path($sitemapUrl->sitemap_path))) {
            File::delete(public_path($sitemapUrl->sitemap_path));
        }

        return (bool) $sitemapUrl->delete();
    }
}
