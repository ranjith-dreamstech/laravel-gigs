<?php

namespace Modules\Page\Repositories\Contracts;

interface SectionInterface
{
    /**
     * @param string $orderBy
     * @param string $sortBy
     * @param int $themeId
     *
     * @return \Illuminate\Support\Collection<int, mixed>
     */
    public function getAllSections(string $orderBy, string $sortBy, int $themeId): \Illuminate\Support\Collection;

    /**
     * @param string $orderBy
     * @param string $sortBy
     * @param array<int, string> $allowedNames
     *
     * @return \Illuminate\Support\Collection<int, mixed>
     */
    public function getFilteredSections(string $orderBy, string $sortBy, array $allowedNames): \Illuminate\Support\Collection;

    /**
     * @param int $sectionId
     * @param int $languageId
     *
     * @return string|null
     */
    public function getSectionData(int $sectionId, int $languageId): string|null;

    /**
     * @param int $sectionId
     * @param int $languageId
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    public function updateOrCreateSectionData(int $sectionId, int $languageId, array $data): bool;

    /**
     * @param int $sectionId
     * @param string $title
     *
     * @return bool
     */
    public function updateSectionTitle(int $sectionId, string $title): bool;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deletePage(int $id): bool;
}
