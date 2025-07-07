<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\TranslationRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    protected TranslationRepositoryInterface $translationRepository;

    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    public function getFileTranslations(string $file, string $modules): JsonResponse
    {
        $translations = $this->translationRepository->getFileTranslations($file, $modules);

        if (isset($translations['error'])) {
            return response()->json(['error' => $translations['error']], 400);
        }

        return response()->json($translations);
    }
}
