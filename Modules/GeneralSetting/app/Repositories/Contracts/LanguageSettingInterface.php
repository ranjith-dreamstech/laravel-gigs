<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\TranslationLanguage;

interface LanguageSettingInterface
{
    /**
     * @return array<string, Collection<int, TranslationLanguage>>
     */
    public function index(): array;

    /**
     * @param array{
     *     lang_id: int
     * } $data
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function addLanguage(array $data): array;

    /**
     * @param array{
     *     search?: string
     * } $filters
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     data: array<string, array{
     *         id: int,
     *         language_name: string,
     *         lang_img: string,
     *         lang_code: string,
     *         lang_rtl: bool,
     *         default: bool,
     *         status: bool,
     *         total_keys: int,
     *         translated_keys: int,
     *         progress: float
     *     }>
     * }
     */
    public function getLanguages(array $filters = []): array;

    /**
     * @param array{
     *     field: string,
     *     value: mixed
     * } $data
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function updateLanguageSettings(int $id, array $data): array;

    /**
     * @return array{
     *     status: string,
     *     message: string
     * }
     */
    public function changeLanguage(string $languageCode): array;

    /**
     * @return array{
     *     status: string,
     *     message: string
     * }
     */
    public function userFlagChangeLanguage(string $languageCode): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     data: array<int, array{
     *         module_name: string,
     *         module_key: string,
     *         total_keys: int,
     *         translated_keys: int,
     *         progress: float
     *     }>
     * }
     */
    public function getLanguageModules(string $code, string $tab, ?string $search = null): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     data: array<int, array{
     *         default: string,
     *         key: string,
     *         value: string
     *     }>,
     *     language: Language,
     *     icon: string,
     *     progress: float,
     *     color: string,
     *     uppercaseName: string
     * }
     */
    public function editModuleLanguage(string $code, string $tab, string $module, ?string $keyword = null): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     language: Language,
     *     icon: string,
     *     uppercaseName: string,
     *     progress: float,
     *     color: string
     * }
     */
    public function updateModuleLanguage(string $code, string $tab, string $module, string $key, string $value): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string
     * }
     */
    public function deleteLanguage(int $id): array;

    /**
     * @return array{
     *     language: Language,
     *     flag: string,
     *     tab: string
     * }
     */
    public function languageDetails(string $code, string $type): array;
}
