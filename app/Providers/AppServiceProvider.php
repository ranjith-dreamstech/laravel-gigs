<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\Language;
use Modules\MenuManagement\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $modulesStatusPath = base_path('modules_statuses.json');

        if (File::exists($modulesStatusPath)) {
            $modulesStatus = json_decode(File::get($modulesStatusPath), true);

            if (isset($modulesStatus['Installer']) && $modulesStatus['Installer'] === true) {
                $this->loadRoutesFrom(base_path('Modules/Installer/routes/web.php'));
            } else {
                $this->globalViews();
                $this->shareThemeAndLayout();
                $this->shareHeader();
                $this->shareFooter();
            }
        } else {
            $this->globalViews();
            $this->shareThemeAndLayout();
            $this->shareHeader();
            $this->shareFooter();
        }
    }

    public function globalViews(): void
    {
        $allLanguages = Language::select('languages.id', 'languages.rtl', 'translation_languages.code', 'translation_languages.name')
            ->join('translation_languages', 'languages.language_id', '=', 'translation_languages.id')
            ->where('languages.status', 1)
            ->get();

        view()->composer('*', function ($view) use ($allLanguages) {
            $permissions = getUserPermissions();
            $appLanguage = App::getLocale();
            $languageId = getLanguageId($appLanguage);
            $copyright = null;
            if ($languageId) {
                $key = 'copy_right_' . $languageId;
                $copyright = GeneralSetting::where('key', $key)
                    ->where('language_id', $languageId)
                    ->value('value');
            }
            $view->with([
                'allLanguages' => $allLanguages,
                'permissions' => $permissions,
                'copyright' => $copyright,
            ]);
        });
    }

    public function shareThemeAndLayout(): void
    {
        view()->composer('*', function ($view) {
            $defaultTheme = GeneralSetting::where('key', 'default_theme')->first();
            $companyPhoneNumber = GeneralSetting::where('key', 'company_phone')->first();
            $companyEmail = GeneralSetting::where('key', 'company_email')->first();
            $companyName = GeneralSetting::where('key', 'organization_name')->first();
            $companyAddress = GeneralSetting::where('key', 'company_address_line')->first();

            $companyPhoneNumber = $companyPhoneNumber ? $companyPhoneNumber->value : '';
            $companyEmail = $companyEmail ? $companyEmail->value : '';
            $companyName = $companyName ? $companyName->value : '';
            $theme = $defaultTheme ? $defaultTheme->value : 1;
            $companyAddress = $companyAddress ? $companyAddress->value : '';

            $logoSetting = GeneralSetting::where('group_id', 16)->pluck('value', 'key')->toArray();
            $logoImage = $logoSetting['logo_image'] ?? null;
            $faviconImage = $logoSetting['favicon_image'] ?? null;
            $smallImage = $logoSetting['small_image'] ?? null;
            $logo = uploadedAsset(is_string($logoImage) ? $logoImage : null, 'default_logo');
            $favicon = uploadedAsset(is_string($faviconImage) ? $faviconImage : null, 'default_favicon');
            $smallLogo = uploadedAsset(is_string($smallImage) ? $smallImage : null, 'default_small_logo');
            $userlayout = session('userlayout') ?? 'buyer';
            $view->with([
                'theme' => $theme,
                'userlayout' => 'frontend.' . $userlayout . '.partials.app',
                'layout' => "frontend.theme_{$theme}.app",
                'companyPhoneNumber' => $companyPhoneNumber,
                'companyEmail' => $companyEmail,
                'companyName' => $companyName,
                'companyAddress' => $companyAddress,
                'logo' => $logo,
                'favicon' => $favicon,
                'smallLogo' => $smallLogo,
            ]);
        });
    }

    public function shareHeader(): void
    {
        view()->composer(['frontend.theme_1.header', 'frontend.theme_2.header'], function ($view) {
            $appLanguage = App::getLocale();
            $languageId = getLanguageId($appLanguage);

            $headers = Menu::where(['menu_type' => 'header', 'status' => 1, 'language_id' => $languageId])
                ->get(['id', 'name', 'menus']);

            $headers->transform(function ($header) {
                $menus = [];
                if (! empty($header->menus)) {
                    $decoded = json_decode($header->menus, true);
                    if (is_array($decoded)) {
                        $menus = $decoded;
                    }
                }
                $filteredMenus = collect($menus)
                    ->filter(fn ($menu) => isset($menu['status']) && $menu['status'] === true)
                    ->values()
                    ->all();

                $header->menus_array = $filteredMenus;

                return $header;
            });

            $view->with([
                'headers' => $headers,
            ]);
        });
    }

    public function shareFooter(): void
    {
        view()->composer(['frontend.theme_1.footer', 'frontend.theme_2.footer'], function ($view) {
            $appLanguage = App::getLocale();
            $languageId = getLanguageId($appLanguage);

            $footers = Menu::where(['menu_type' => 'footer', 'status' => 1, 'language_id' => $languageId])
                ->get(['id', 'name', 'menus']);

            $footers->transform(function ($footer) {
                $menus = [];
                if (! empty($footer->menus)) {
                    $decoded = json_decode($footer->menus, true);
                    if (is_array($decoded)) {
                        $menus = $decoded;
                    }
                }
                $filteredMenus = collect($menus)
                    ->filter(function ($menu) {
                        return ! empty($menu['status']);
                    })
                    ->values()
                    ->all();

                $footer->parsed_menus = $filteredMenus;
                return $footer;
            });

            $view->with([
                'footers' => $footers,
            ]);
        });
    }
}
