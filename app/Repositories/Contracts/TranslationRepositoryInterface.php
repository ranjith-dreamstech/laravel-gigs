<?php

namespace App\Repositories\Contracts;

interface TranslationRepositoryInterface
{
    /** @return array <string, mixed> */
    public function getFileTranslations(string $file, string $modules): array;
}
