<?php

namespace Modules\Page\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Page\Models\Page;
use Modules\Page\Models\Section;
use Modules\Page\Repositories\Contracts\SectionInterface;

class SectionRepository implements SectionInterface
{
    /**
     * @param mixed $orderBy
     * @param mixed $sortBy
     * @param mixed $themeId
     *
     * @return Collection<int, Section>
     */
    public function getAllSections(mixed $orderBy, mixed $sortBy, mixed $themeId): Collection
    {
        return Section::orderBy($sortBy, $orderBy)
            ->where('theme_id', $themeId)
            ->where('status', 1)
            ->get();
    }

    /**
     * @param mixed $orderBy
     * @param mixed $sortBy
     * @param mixed $allowedNames
     *
     * @return Collection<int, Section>
     */
    public function getFilteredSections($orderBy, $sortBy, mixed $allowedNames): Collection
    {
        return Section::orderBy($sortBy, $orderBy)
            ->where('status', 1)
            ->whereIn('name', is_array($allowedNames) ? $allowedNames : [])
            ->get();
    }

    /**
     * @param mixed $sectionId
     * @param mixed $languageId
     *
     * @return string|null
     */
    public function getSectionData(mixed $sectionId, mixed $languageId): ?string
    {
        return DB::table('section_datas')
            ->where('section_id', $sectionId)
            ->where('language_id', $languageId)
            ->value('datas');
    }

    /**
     * @param mixed $sectionId
     * @param mixed $languageId
     * @param mixed $data
     *
     * @return bool
     */
    public function updateOrCreateSectionData(mixed $sectionId, mixed $languageId, mixed $data): bool
    {
        $updated = DB::table('section_datas')
            ->where('section_id', $sectionId)
            ->where('language_id', $languageId)
            ->update(['datas' => json_encode($data)]);

        if ($updated === 0) {
            DB::table('section_datas')->insert([
                'section_id' => $sectionId,
                'language_id' => $languageId,
                'datas' => json_encode($data),
            ]);
            return true;
        }

        return true;
    }

    /**
     * @param mixed $sectionId
     * @param mixed $title
     *
     * @return bool
     */
    public function updateSectionTitle(mixed $sectionId, mixed $title): bool
    {
        $section = Section::find($sectionId);

        if (! $section) {
            return false;
        }

        $section->title = $title;

        return $section->save();
    }

    /**
     * @param mixed $id
     *
     * @return bool
     */
    public function deletePage(mixed $id): bool
    {
        return Page::where('id', $id)->delete() > 0;
    }
}
