<?php

use App\Services\ApiResponseService;
use App\Services\CurrencyService;
use App\Services\FileUploadService;
use App\Services\NotificationService;
use App\Services\PermissionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

if (!function_exists('fileUpload')) {
    /**
     * Upload file using FileUploadService
     */
    function fileUpload(UploadedFile $file, string $path = 'uploads', ?string $oldFileName = null): ?string
    {
        return app(FileUploadService::class)->uploadFile($file, $path, $oldFileName);
    }
}

if (!function_exists('uploadedAsset')) {
    /**
     * Get uploaded asset URL
     */
    function uploadedAsset(string|null $filePath, string|null $default = 'default'): string
    {
        $details = app(FileUploadService::class)->getFileDetails($filePath, $default);
        return $details['url'];
    }
}

if (!function_exists('uploadedAssetDetails')) {
    /**
     * Get uploaded asset details
     */
    function uploadedAssetDetails(string|null $filePath, string|null $default = 'default'): array
    {
        return app(FileUploadService::class)->getFileDetails($filePath, $default);
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Format file size
     */
    function formatFileSize(int|string $bytes): string
    {
        return app(FileUploadService::class)->formatFileSize((int) $bytes);
    }
}

if (!function_exists('sendNotification')) {
    /**
     * Send notification using NotificationService
     */
    function sendNotification(string $email, string $slug, array $notifyData = []): bool
    {
        return app(NotificationService::class)->sendNotification($email, $slug, $notifyData);
    }
}

if (!function_exists('sendNewsletterEmail')) {
    /**
     * Send newsletter email
     */
    function sendNewsletterEmail(string|array $email, string $slug, array $notifyData): bool
    {
        return app(NotificationService::class)->sendNewsletterEmail($email, $slug, $notifyData);
    }
}

if (!function_exists('gigNotificationEnabled')) {
    /**
     * Check if gig notifications are enabled
     */
    function gigNotificationEnabled(): bool
    {
        return app(NotificationService::class)->isGigNotificationEnabled();
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Get user permissions using PermissionService
     */
    function getUserPermissions(int|string|null $userId = null): \Illuminate\Support\Collection
    {
        return app(PermissionService::class)->getUserPermissions($userId);
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Check user permission
     */
    function hasPermission(string|array $moduleSlug, string $action, ?int $userId = null): bool
    {
        return app(PermissionService::class)->hasPermission($moduleSlug, $action, $userId);
    }
}

if (!function_exists('current_user')) {
    /**
     * Get current user
     */
    function current_user(?string $guard = null): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return app(PermissionService::class)->getCurrentUser($guard);
    }
}

if (!function_exists('getDefaultCurrencySymbol')) {
    /**
     * Get default currency symbol
     */
    function getDefaultCurrencySymbol(): string
    {
        return app(CurrencyService::class)->getDefaultCurrencySymbol();
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format price with currency
     */
    function formatPrice(float|int $price, bool $withSymbol = true, bool $rawPrice = false): string|float|int
    {
        return app(CurrencyService::class)->formatPrice($price, $withSymbol, $rawPrice);
    }
}

if (!function_exists('buildResponse')) {
    /**
     * Build API response (legacy support)
     */
    function buildResponse(int $code, bool $success, string $message, $data = null, $error = null): array
    {
        return ApiResponseService::buildResponse($code, $success, $message, $data, $error);
    }
}

if (!function_exists('clearCache')) {
    /**
     * Clear application cache
     */
    function clearCache(): bool
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format date time with settings
     */
    function formatDateTime(mixed $date, bool $includeTime = true, bool $timeOnly = false): string
    {
        return Cache::remember('datetime_format_' . md5((string) $date . $includeTime . $timeOnly), 300, function () use ($date, $includeTime, $timeOnly) {
            $generalSettings = \Modules\GeneralSetting\Models\GeneralSetting::where('group_id', 5)->pluck('value', 'key');

            $dateFormat = 'Y-m-d';
            $timeFormat = 'H:i:s';

            if ($generalSettings->isNotEmpty()) {
                $dateFormat = optional(\Modules\GeneralSetting\Models\DateFormat::find($generalSettings->get('date_format')))->name ?? $dateFormat;
                $timeFormat = optional(\Modules\GeneralSetting\Models\TimeFormat::find($generalSettings->get('time_format')))->name ?? $timeFormat;
            }

            if ($timeOnly) {
                $format = $timeFormat;
            } elseif ($includeTime) {
                $format = "{$dateFormat} {$timeFormat}";
            } else {
                $format = $dateFormat;
            }

            try {
                return \Illuminate\Support\Carbon::parse($date)->format($format);
            } catch (\Throwable $th) {
                return (string) $date;
            }
        });
    }
}

if (!function_exists('getBaseUrl')) {
    /**
     * Get base URL
     */
    function getBaseUrl(): string
    {
        return config('app.url') ?: request()->getSchemeAndHttpHost();
    }
}

if (!function_exists('getGeneralSetting')) {
    /**
     * Get general setting value with caching
     */
    function getGeneralSetting(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = \Modules\GeneralSetting\Models\GeneralSetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}

if (!function_exists('isRTL')) {
    /**
     * Check if language is RTL
     */
    function isRTL(?string $languageCode = null): int|string
    {
        return Cache::remember("rtl_{$languageCode}", 3600, function () use ($languageCode) {
            $language = \Modules\GeneralSetting\Models\TranslationLanguage::select('id')->where('code', $languageCode)->first();
            if ($language) {
                $languageId = $language->id;
                $language = \Modules\GeneralSetting\Models\Language::select('rtl')->where('language_id', $languageId)->first();
                if ($language && isset($language->rtl)) {
                    return $language->rtl;
                }
            }
            return 0;
        });
    }
}

if (!function_exists('getLanguageId')) {
    /**
     * Get language ID by code
     */
    function getLanguageId(string $langCode = 'en'): int
    {
        return Cache::remember("lang_id_{$langCode}", 3600, function () use ($langCode) {
            $languageId = \Modules\GeneralSetting\Models\TranslationLanguage::where('code', $langCode)->value('id');
            return $languageId ?? 1;
        });
    }
}

if (!function_exists('getCurrentUserImage')) {
    /**
     * Get current user profile image
     */
    function getCurrentUserImage(): string
    {
        $user = current_user();
        if (!$user) {
            return uploadedAsset(null, 'profile');
        }

        return Cache::remember("user_image_{$user->id}", 1800, function () use ($user) {
            return $user->userDetail && $user->userDetail->profile_image
                ? uploadedAsset($user->userDetail->profile_image)
                : uploadedAsset(null, 'profile');
        });
    }
}

if (!function_exists('getCurrentUserFullname')) {
    /**
     * Get current user full name
     */
    function getCurrentUserFullname(?int $userId = null): string
    {
        if ($userId) {
            return Cache::remember("user_fullname_{$userId}", 1800, function () use ($userId) {
                $user = \App\Models\User::where('id', $userId)->first();
                $userDetails = \App\Models\UserDetail::where('user_id', $userId)->first();

                $fullName = $user ? ($user->name ?? '') : '';

                if ($userDetails && $userDetails->first_name && $userDetails->last_name) {
                    $fullName = trim(($userDetails->first_name ?? '') . ' ' . ($userDetails->last_name ?? ''));
                }

                return $fullName;
            });
        }

        $user = current_user();
        if (!$user) {
            return '';
        }

        return Cache::remember("current_user_fullname_{$user->id}", 1800, function () use ($user) {
            if ($user->userDetail && $user->userDetail->first_name) {
                return trim(($user->userDetail->first_name ?? '') . ' ' . ($user->userDetail->last_name ?? ''));
            }

            return $user->name ?? '';
        });
    }
}

if (!function_exists('reviewExists')) {
    /**
     * Check if review exists
     */
    function reviewExists(?int $gigsId = null, ?int $userId = null): bool
    {
        if (!$gigsId || !$userId) {
            return false;
        }

        return Cache::remember("review_exists_{$gigsId}_{$userId}", 1800, function () use ($gigsId, $userId) {
            return \App\Models\Review::where('gigs_id', $gigsId)
                ->where('user_id', $userId)
                ->exists();
        });
    }
}

if (!function_exists('isBookingCompleted')) {
    /**
     * Check if booking is completed
     */
    function isBookingCompleted(?int $gigId = null, ?int $userId = null): bool
    {
        if (!$gigId || !$userId) {
            return false;
        }

        return Cache::remember("booking_completed_{$gigId}_{$userId}", 900, function () use ($gigId, $userId) {
            return \Modules\Booking\Models\Booking::where('gigs_id', $gigId)
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->exists();
        });
    }
}

if (!function_exists('gigOwnerUserId')) {
    /**
     * Get gig owner user ID
     */
    function gigOwnerUserId(?int $gigId = null): ?int
    {
        if (!$gigId) {
            return null;
        }

        return Cache::remember("gig_owner_{$gigId}", 3600, function () use ($gigId) {
            return \App\Models\Gigs::where('id', $gigId)->value('user_id');
        });
    }
}

if (!function_exists('getUserWalletBalance')) {
    /**
     * Get user wallet balance
     */
    function getUserWalletBalance(int $userId): array
    {
        return Cache::remember("wallet_balance_{$userId}", 300, function () use ($userId) {
            $wallet = \Modules\Finance\Models\Wallet::where('user_id', $userId)->first();
            
            return [
                'available_balance' => $wallet ? (float) $wallet->available_balance : 0.0,
                'pending_balance' => $wallet ? (float) $wallet->pending_balance : 0.0,
                'total_balance' => $wallet ? (float) ($wallet->available_balance + $wallet->pending_balance) : 0.0,
            ];
        });
    }
}

if (!function_exists('customEncrypt')) {
    /**
     * Custom encryption function (consider using Laravel's built-in encryption instead)
     */
    function customEncrypt(string|int|null $data, string $key = 'default_secret_key'): string
    {
        try {
            return encrypt((string) $data);
        } catch (\Exception $e) {
            // Fallback to custom encryption if needed
            $cipher = 'AES-128-CBC';
            $iv = substr(hash('sha256', $key, true), 0, 16);
            $encrypted = openssl_encrypt((string) $data, $cipher, $key, 0, $iv);

            if ($encrypted === false) {
                return '';
            }

            return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
        }
    }
}

if (!function_exists('customDecrypt')) {
    /**
     * Custom decryption function
     */
    function customDecrypt(string|int|null $encryptedData, string $key = 'default_secret_key'): ?string
    {
        try {
            return decrypt((string) $encryptedData);
        } catch (\Exception $e) {
            // Fallback to custom decryption if needed
            $cipher = 'AES-128-CBC';
            $iv = substr(hash('sha256', $key, true), 0, 16);

            $encryptedData = strtr((string) $encryptedData, '-_', '+/');
            $decoded = base64_decode($encryptedData, true);

            if ($decoded === false) {
                return null;
            }

            $decrypted = openssl_decrypt($decoded, $cipher, $key, 0, $iv);

            return $decrypted !== false ? $decrypted : null;
        }
    }
}