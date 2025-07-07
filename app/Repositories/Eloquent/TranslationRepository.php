<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\TranslationRepositoryInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class TranslationRepository implements TranslationRepositoryInterface
{
    /**
     * @return array <string, mixed>
     */
    public function getFileTranslations(string $file, string $modules): array
    {
        $locale = App::getLocale();

        $validFiles = ['web', 'admin', 'app'];
        if (! in_array($file, $validFiles)) {
            return ['error' => 'Invalid translation file'];
        }

        $moduleArray = array_map('trim', explode(',', $modules));
        $translations = [$file => []];

        foreach ($moduleArray as $module) {
            if (Lang::has("{$file}.{$module}", $locale)) {
                $translations[$file][$module] = trans("{$file}.{$module}", [], $locale);
            } else {
                $translations[$file][$module] = [];
            }
        }

        return $translations;
    }
}
