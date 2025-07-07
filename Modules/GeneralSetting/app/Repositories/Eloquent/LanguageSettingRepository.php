<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\TranslationLanguage;
use Modules\GeneralSetting\Repositories\Contracts\LanguageSettingInterface;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class LanguageSettingRepository implements LanguageSettingInterface
{
    private const FLAG_IMAGE_PATH = 'backend/assets/img/flags/';

    /**
     * @return array<string, Collection<int, TranslationLanguage>>
     */
    public function index(): array
    {
        return [
            'translationLanguages' => TranslationLanguage::where('status', 1)->get(),
        ];
    }

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
    public function addLanguage(array $data): array
    {
        $response = [
            'status' => 'error',
            'code' => 422,
            'message' => __('admin.general_settings.retrive_error'),
        ];

        $languageTranslation = TranslationLanguage::find($data['lang_id']);

        if (! $languageTranslation) {
            $response['message'] = __('admin.general_settings.language_not_found');
            return $response;
        }

        if (Language::where('language_id', $languageTranslation->id)->exists()) {
            $response['message'] = __('admin.general_settings.language_already_exist');
            return $response;
        }

        try {
            Language::create(['language_id' => $languageTranslation->id]);

            $langPath = base_path('resources/lang/' . $languageTranslation->code);

            if (! file_exists($langPath)) {
                mkdir($langPath, 0777, true);
            }

            $this->initializeLanguageFiles($languageTranslation->code);

            $response = [
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.language_added_successfully'),
            ];
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

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
    public function getLanguages(array $filters = []): array
    {
        $languages = Language::query();

        if (isset($filters['search']) && $filters['search'] !== '') {
            $languages->where(function ($query) use ($filters) {
                $query->whereHas('transLang', function ($query) use ($filters) {
                    $query->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('code', 'like', "%{$filters['search']}%");
                });
            });
        }

        $languages = $languages->with('transLang')->get();
        $langDefaultFiles = ['admin.php', 'web.php'];
        $responseArray = [];
        $totalKeys = $this->countTotalTranslationKeys($langDefaultFiles);

        foreach ($languages as $language) {
            if (! $language->transLang) {
                continue;
            }

            $translatedCount = $this->countTranslatedKeys($language->transLang->code, $langDefaultFiles);
            $progress = $totalKeys > 0 ? round($translatedCount / $totalKeys * 100, 2) : 0;

            $responseArray[$language->transLang->code] = [
                'id' => $language->id,
                'language_name' => $language->transLang->name,
                'lang_img' => url(self::FLAG_IMAGE_PATH . $language->transLang->code . '.svg'),
                'lang_code' => $language->transLang->code,
                'lang_rtl' => (bool) $language->rtl,
                'default' => (bool) $language->default,
                'status' => (bool) $language->status,
                'total_keys' => $totalKeys,
                'translated_keys' => $translatedCount,
                'progress' => $progress,
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => __('admin.general_settings.language_fetched_successfully'),
            'data' => $responseArray,
        ];
    }

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
    public function updateLanguageSettings(int $id, array $data): array
    {
        try {
            $language = Language::findOrFail($id);
            $languageCode = $language->transLang->code ?? null;
            $field = $data['field'];

            if ($field === 'default') {
                Language::where('default', 1)->update(['default' => 0]);
                session()->forget(['app_locale', 'app_locale_user']);
                session(['app_locale' => $languageCode, 'app_locale_user' => $languageCode]);

                $adminUser = Auth::guard('admin')->user();
                if ($adminUser instanceof \App\Models\User) {
                    $adminUser->update(['language_id' => $language->language_id]);
                }
            }

            $language->update([$field => $data['value']]);

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.language_updated_successfully'),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $th->getMessage(),
            ];
        }
    }

    /**
     * @return array{
     *     status: string,
     *     message: string
     * }
     */
    public function changeLanguage(string $languageCode): array
    {
        $language = TranslationLanguage::where('code', $languageCode)->first();

        if (! $language) {
            return [
                'status' => 'error',
                'message' => __('admin.general_settings.language_not_found'),
            ];
        }

        session(['app_locale' => $languageCode]);

        $adminUser = Auth::guard('admin')->user();
        if ($adminUser instanceof \App\Models\User) {
            $adminUser->update(['language_id' => $language->id]);
        }

        app()->setLocale($languageCode);

        return [
            'status' => 'success',
            'message' => __('admin.general_settings.language_changed_successfully'),
        ];
    }
    /**
     * @return array{
     *     status: string,
     *     message: string
     * }
     */
    public function userFlagChangeLanguage(string $languageCode): array
    {
        $language = TranslationLanguage::where('code', $languageCode)->first();

        if (! $language) {
            return [
                'status' => 'error',
                'message' => __('admin.general_settings.language_not_found'),
            ];
        }

        session(['app_locale_user' => $languageCode]);

        $user = Auth::guard('web')->user();
        if ($user instanceof \App\Models\User) {
            $user->update(['language_id' => $language->id]);
        }

        return [
            'status' => 'success',
            'message' => __('admin.general_settings.language_changed_successfully'),
        ];
    }

    /**
     * @return array{
     *     language: Language,
     *     flag: string,
     *     tab: string
     * }
     */
    public function languageDetails(string $code, string $type): array
    {
        $validTabs = ['admin', 'web'];
        if (! in_array($type, $validTabs)) {
            abort(404);
        }

        $language = Language::with('transLang')
            ->whereHas('transLang', fn ($query) => $query->where('code', $code))
            ->firstOrFail();

        $langCode = $language->transLang->code ?? null;
        $flag = asset(self::FLAG_IMAGE_PATH . $langCode . '.svg');

        return [
            'language' => $language,
            'flag' => $flag,
            'tab' => $type,
        ];
    }

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
    public function getLanguageModules(string $code, string $tab, ?string $search = null): array
    {
        $validTabs = ['admin', 'web'];
        $emptyData = [];

        if (! in_array($tab, $validTabs)) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Invalid tab provided',
                'data' => $emptyData,
            ];
        }

        $language = Language::whereHas('transLang', fn ($query) => $query->where('code', $code))
            ->with('transLang')->first();

        if (! $language) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => __('admin.general_settings.language_not_found'),
                'data' => $emptyData,
            ];
        }

        $langCode = $language->transLang->code ?? null;
        $defaultLang = 'en';
        $filePath = base_path("resources/lang/{$defaultLang}/{$tab}.php");
        $translatedPath = base_path("resources/lang/{$langCode}/{$tab}.php");
        $responseArray = [];

        $defaultTranslations = file_exists($filePath) ? include_once $filePath : [];
        $translatedTranslations = file_exists($translatedPath) ? include_once $translatedPath : [];

        foreach ($defaultTranslations as $module => $keys) {
            if ($search && ! str_contains($module, $search)) {
                continue;
            }

            $totalKeys = $this->countKeys($keys);
            $translatedCount = isset($translatedTranslations[$module])
                ? $this->countKeys($translatedTranslations[$module], true)
                : 0;

            $progress = $totalKeys > 0 ? round($translatedCount / $totalKeys * 100, 2) : 0;

            $responseArray[] = [
                'module_name' => ucfirst(str_replace('_', ' ', $module)),
                'module_key' => $module,
                'total_keys' => $totalKeys,
                'translated_keys' => $translatedCount,
                'progress' => $progress,
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => __('admin.general_settings.module_fetched_success'),
            'data' => $responseArray,
        ];
    }

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
    public function editModuleLanguage(string $code, string $tab, string $module, ?string $keyword = null): array
    {
        $emptyLanguage = new Language();

        if (! in_array($tab, ['admin', 'web'])) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => __('admin.general_settings.invalid_tab'),
                'data' => [],
                'language' => $emptyLanguage,
                'icon' => '',
                'progress' => 0,
                'color' => '',
                'uppercaseName' => '',
            ];
        }

        $language = Language::whereHas('transLang', fn ($query) => $query->where('code', $code))
            ->with('transLang')->first();

        if (! $language) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => __('admin.general_settings.language_not_found'),
                'data' => [],
                'language' => $emptyLanguage,
                'icon' => '',
                'progress' => 0,
                'color' => '',
                'uppercaseName' => '',
            ];
        }

        $langCode = $language->transLang->code ?? '';
        $defaultLang = 'en';
        $filePath = base_path("resources/lang/{$defaultLang}/{$tab}.php");
        $translatedPath = base_path("resources/lang/{$langCode}/{$tab}.php");
        $defaultTranslations = file_exists($filePath) ? include_once $filePath : [];
        $translatedTranslations = file_exists($translatedPath) ? include_once $translatedPath : [];
        $moduleKeys = $defaultTranslations[$module] ?? [];
        $translatedModuleKeys = $translatedTranslations[$module] ?? [];
        $responseArray = [];
        $translatedCount = 0;
        $totalKeys = count($moduleKeys);

        if (! empty($keyword)) {
            $moduleKeys = array_filter($moduleKeys, function ($key, $value) use ($keyword) {
                return stripos($key, $keyword) !== false || stripos($value, $keyword) !== false;
            }, ARRAY_FILTER_USE_BOTH);
            $totalKeys = count($moduleKeys);
        }

        foreach ($moduleKeys as $key => $value) {
            $translatedValue = $translatedModuleKeys[$key] ?? '';
            if (! empty($translatedValue)) {
                $translatedCount++;
            }

            $responseArray[] = [
                'default' => $value,
                'key' => $key,
                'value' => $translatedValue,
            ];
        }

        $progress = $totalKeys > 0 ? round($translatedCount / $totalKeys * 100, 2) : 0;
        $color = $this->getProgressColor($progress);

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Module keys fetched successfully',
            'data' => $responseArray,
            'language' => $language,
            'icon' => $langCode ? url(self::FLAG_IMAGE_PATH . $langCode . '.svg') : '',
            'progress' => $progress,
            'color' => $color,
            'uppercaseName' => strtoupper($language->transLang->name ?? ''),
        ];
    }

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
    public function updateModuleLanguage(string $code, string $tab, string $module, string $key, string $value): array
    {
        $language = Language::whereHas('transLang', fn ($query) => $query->where('code', $code))
            ->with('transLang')->first();

        // Create empty language object for error case
        $emptyLanguage = new Language();

        if (! $language) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => __('admin.general_settings.language_not_found'),
                'language' => $emptyLanguage,
                'icon' => '',
                'uppercaseName' => '',
                'progress' => 0,
                'color' => '',
            ];
        }

        $langCode = $language->transLang->code ?? null;
        $translatedPath = base_path("resources/lang/{$langCode}/{$tab}.php");
        $translatedTranslations = file_exists($translatedPath) ? include_once $translatedPath : [];

        if (! isset($translatedTranslations[$module])) {
            $translatedTranslations[$module] = [];
        }

        $translatedTranslations[$module][$key] = $value;
        file_put_contents($translatedPath, '<?php return ' . var_export($translatedTranslations, true) . ';');

        $progress = $langCode ? $this->calculateModuleProgress($langCode, $tab, $module) : 0;
        $color = $this->getProgressColor($progress);

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Module key updated successfully',
            'language' => $language,
            'icon' => $langCode ? url(self::FLAG_IMAGE_PATH . $langCode . '.svg') : '',
            'uppercaseName' => strtoupper($language->transLang->name ?? ''),
            'progress' => $progress,
            'color' => $color,
        ];
    }

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string
     * }
     */
    public function deleteLanguage(int $id): array
    {
        $language = Language::findOrFail($id);
        $systemLanguage = 'en';
        $langCode = $language->transLang->code ?? null;

        if ($langCode === $systemLanguage) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => __('admin.general_settings.cannot_delete_default_language'),
            ];
        }

        if ($language->default === 1) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => __('admin.general_settings.cannot_delete_default_language'),
            ];
        }

        if ($language->transLang && $language->transLang->code) {
            $langPath = base_path('resources/lang/' . $language->transLang->code);
            if (is_dir($langPath)) {
                File::deleteDirectory($langPath);
            }
        }

        $language->delete();

        return [
            'status' => 'success',
            'code' => 200,
            'message' => __('admin.general_settings.language_deleted'),
        ];
    }

    // Helper Methods

    protected function initializeLanguageFiles(string $langCode): void
    {
        $defaultLang = 'en';
        $langDefaultFiles = ['admin.php', 'web.php'];

        foreach ($langDefaultFiles as $file) {
            $sourcePath = base_path("resources/lang/{$defaultLang}/{$file}");
            $destinationPath = base_path("resources/lang/{$langCode}/{$file}");

            if (file_exists($sourcePath)) {
                $translations = include_once $sourcePath;
                $clearedTranslations = array_map(function ($module) {
                    return array_map(function () {
                        return '';
                    }, $module);
                }, $translations);

                $exportedTranslations = var_export($clearedTranslations, true);
                $exportedTranslations = str_replace('array (', '[', $exportedTranslations);
                $exportedTranslations = str_replace(')', ']', $exportedTranslations);

                file_put_contents($destinationPath, "<?php\nreturn " . $exportedTranslations . ";\n");
            }
        }
    }

    /**
     * Counts total translation keys in default language files
     *
     * @param array<string> $files Array of language file names to check (e.g., ['admin.php', 'web.php'])
     *
     * @return int Total number of translation keys found
     */
    protected function countTotalTranslationKeys(array $files): int
    {
        $totalKeys = 0;
        $defaultLang = 'en';

        foreach ($files as $file) {
            $filePath = base_path("resources/lang/{$defaultLang}/{$file}");
            if (file_exists($filePath)) {
                $defaultTranslations = include_once $filePath;
                if (is_array($defaultTranslations)) {
                    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($defaultTranslations));
                    $totalKeys += iterator_count($iterator);
                }
            }
        }

        return $totalKeys;
    }

    /**
     * Counts the number of translated keys in language files
     *
     * @param string $langCode The language code (e.g., 'en', 'fr')
     * @param array<string> $files Array of language file names to check (e.g., ['admin.php', 'web.php'])
     *
     * @return int Number of translated keys found
     */
    protected function countTranslatedKeys(string $langCode, array $files): int
    {
        $translatedCount = 0;

        foreach ($files as $file) {
            $filePath = base_path("resources/lang/{$langCode}/{$file}");
            if (file_exists($filePath)) {
                $translatedKeys = include_once $filePath;
                if (is_array($translatedKeys)) {
                    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($translatedKeys));
                    foreach ($iterator as $value) {
                        if (! empty($value)) {
                            $translatedCount++;
                        }
                    }
                }
            }
        }

        return $translatedCount;
    }

    /**
     * Counts keys in a nested array structure, optionally counting only non-empty values
     *
     * @param array<string|array<mixed>> $array The array to count keys in
     * @param bool $checkEmpty Whether to count only non-empty values
     *
     * @return int The count of keys
     */
    protected function countKeys(array $array, bool $checkEmpty = false): int
    {
        $count = 0;
        foreach ($array as $value) {
            if (is_array($value)) {
                $count += $this->countKeys($value, $checkEmpty);
            } elseif (! $checkEmpty || ! empty($value)) {
                $count++;
            }
        }
        return $count;
    }

    protected function getProgressColor(float $progress): string
    {
        $color = 'bg-danger';

        if ($progress >= 100) {
            $color = 'bg-success';
        } elseif ($progress >= 75) {
            $color = 'bg-pink';
        } elseif ($progress >= 50) {
            $color = 'bg-warning';
        } elseif ($progress >= 25) {
            $color = 'bg-danger';
        }

        return $color;
    }

    protected function calculateModuleProgress(string $langCode, string $tab, string $module): float
    {
        $defaultLang = 'en';
        $defaultPath = base_path("resources/lang/{$defaultLang}/{$tab}.php");
        $translatedPath = base_path("resources/lang/{$langCode}/{$tab}.php");

        $defaultTranslations = file_exists($defaultPath) ? include_once $defaultPath : [];
        $translatedTranslations = file_exists($translatedPath) ? include_once $translatedPath : [];

        $moduleKeys = $defaultTranslations[$module] ?? [];
        $translatedModuleKeys = $translatedTranslations[$module] ?? [];

        $translatedCount = 0;
        $totalKeys = count($moduleKeys);

        foreach ($moduleKeys as $key => $value) {
            if (! empty($translatedModuleKeys[$key] ?? '')) {
                $translatedCount++;
            }
        }

        return $totalKeys > 0 ? round($translatedCount / $totalKeys * 100, 2) : 0;
    }
}
