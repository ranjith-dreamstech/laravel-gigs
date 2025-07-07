<?php

use App\Models\Gigs;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Booking\Models\Booking;
use Modules\Communication\Http\Controllers\EmailController;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\DateFormat;
use Modules\GeneralSetting\Models\EmailTemplate;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\NotificationType;
use Modules\GeneralSetting\Models\TimeFormat;
use Modules\GeneralSetting\Models\TranslationLanguage;
use Modules\RolesPermission\Models\Permission;

if (! function_exists('clearCache')) {
    function clearCache(): bool
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');

        return true;
    }
}

if (! function_exists('uploadFile')) {
    function uploadFile(UploadedFile $file, string $path = 'uploads', ?string $oldFileName = ''): ?string
    {
        $activeDisk = config('filesystems.default');

        if ($file->isValid()) {
            $oldFileName = $oldFileName ?? '';
            if (Storage::disk($activeDisk)->exists($oldFileName)) {
                Storage::disk($activeDisk)->delete($oldFileName);
            }

            $filename = str_replace(',', '', Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension());
            $file->storeAs($path, $filename, $activeDisk);

            return $path . '/' . $filename;
        }

        return null;
    }
}

if (! function_exists('uploadMutipleFile')) {
    function uploadMutipleFile(UploadedFile $file, string $path = 'uploads', ?string $oldFileName = '', string $disk = 'public'): ?string
    {
        $activeDisk = $disk ?: config('filesystems.default');

        if ($file->isValid()) {
            $oldFileName = $oldFileName ?? '';
            if ($oldFileName && Storage::disk($activeDisk)->exists("{$path}/{$oldFileName}")) {
                Storage::disk($activeDisk)->delete("{$path}/{$oldFileName}");
            }

            $filename = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($path, $filename, $activeDisk);

            return $filename;
        }

        return null;
    }
}

if (! function_exists('formatDateTime')) {
    function formatDateTime(mixed $date, bool $includeTime = true, bool $timeOnly = false): string
    {
        $generalSettings = GeneralSetting::where('group_id', 5)->pluck('value', 'key');

        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';

        if ($generalSettings->isNotEmpty()) {
            $dateFormat = optional(DateFormat::find($generalSettings->get('date_format')))->name ?? $dateFormat;
            $timeFormat = optional(TimeFormat::find($generalSettings->get('time_format')))->name ?? $timeFormat;
        }

        if ($timeOnly) {
            $format = $timeFormat;
        } elseif ($includeTime) {
            $format = "{$dateFormat} {$timeFormat}";
        } else {
            $format = $dateFormat;
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (\Throwable $th) {
            return (string) $date;
        }
    }
}

if (! function_exists('uploadedAsset')) {
    /**
     * @param string $filePath
     * @param string $default
     *
     * @return string
     */
    function uploadedAsset(string|null $filePath, string|null $default = ''): string
    {
        $disk = config('filesystems.default');

        // Use getBaseUrl() for consistent domain
        $baseUrl = getBaseUrl();

        // Default response structure
        $defaultImages = [
            'profile' => $baseUrl . '/backend/assets/img/default-profile.png',
            'default2' => $baseUrl . '/backend/assets/img/default-placeholder-image.png',
            'default' => $baseUrl . '/backend/assets/img/default-image-02.jpg',
            'default_logo' => $baseUrl . '/backend/assets/img/logo.svg',
            'default_small_logo' => $baseUrl . '/frontend/assets/img/logo-small.svg',
            'default_favicon' => $baseUrl . '/backend/assets/img/favicon.png',
        ];

        // If file does not exist, return default image
        if (! $filePath || ! Storage::disk($disk)->exists($filePath)) {
            return $defaultImages[$default] ?? $defaultImages['default'];
        }

        // Get file details
        $fileUrl = Storage::disk($disk)->url($filePath);

        // Format URL properly for public/local disks
        if ($disk === 'public' || $disk === 'local') {
            // Ensure that parse_url returns a valid string before calling ltrim
            $urlPath = parse_url($fileUrl, PHP_URL_PATH);
            $fileUrl = $baseUrl . '/' . (is_string($urlPath) ? ltrim($urlPath, '/') : '');
        }
        return $fileUrl;
    }
}

if (! function_exists('uploadedAssetDetails')) {
    /**
     * @param string $filePath
     * @param string $default
     *
     * @return array{url: string, file_name?: string, extension?: string, size?: string}
     */
    function uploadedAssetDetails(string|null $filePath, string|null $default = ''): array
    {
        $disk = config('filesystems.default');

        // Use getBaseUrl() for consistent domain
        $baseUrl = getBaseUrl();

        // Default response structure
        $defaultImages = [
            'profile' => $baseUrl . '/backend/assets/img/default-profile.png',
            'default2' => $baseUrl . '/backend/assets/img/default-placeholder-image.png',
            'default' => $baseUrl . '/backend/assets/img/default-image-02.jpg',
            'default_logo' => $baseUrl . '/backend/assets/img/logo.svg',
            'default_small_logo' => $baseUrl . '/frontend/assets/img/logo-small.png',
            'default_favicon' => $baseUrl . '/backend/assets/img/favicon.png',
        ];

        // If file does not exist, return default image
        if (! $filePath || ! Storage::disk($disk)->exists($filePath)) {
            return ['url' => $defaultImages[$default] ?? $defaultImages['default'], 'extension' => '', 'size' => '200 KB'];
        }

        // Get file details
        $fileUrl = Storage::disk($disk)->url($filePath);
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileSize = Storage::disk($disk)->size($filePath);
        $formattedSize = formatFileSize($fileSize);

        // Format URL properly for public/local disks
        if ($disk === 'public' || $disk === 'local') {
            // Ensure that parse_url returns a valid string before calling ltrim
            $urlPath = parse_url($fileUrl, PHP_URL_PATH);
            $fileUrl = $baseUrl . '/' . (is_string($urlPath) ? ltrim($urlPath, '/') : '');
        }
        return ['url' => $fileUrl, 'file_name' => $fileName, 'extension' => $fileExtension, 'size' => $formattedSize];
    }
}

/**
 * Encrypts data using AES-128-CBC encryption.
 *
 * @param string $data The data to be encrypted.
 * @param string $key The encryption key (optional).
 *
 * @return string The encrypted and encoded string, or an empty string on failure.
 */
function customEncrypt(string|int|null $data, string $key = 'default_secret_key'): string
{
    $cipher = 'AES-128-CBC';
    $iv = substr(hash('sha256', $key, true), 0, 16);
    $encrypted = openssl_encrypt((string) $data, $cipher, $key, 0, $iv);

    if ($encrypted === false) {
        return ''; // or throw an exception depending on your needs
    }

    return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
}

function customDecrypt(string|int|null $encryptedData, string $key = 'default_secret_key'): ?string
{
    $cipher = 'AES-128-CBC';
    $iv = substr(hash('sha256', $key, true), 0, 16);

    $encryptedData = strtr((string) $encryptedData, '-_', '+/');
    $decoded = base64_decode($encryptedData, true);

    if ($decoded === false) {
        return null; // base64 decode failed
    }

    $decrypted = openssl_decrypt($decoded, $cipher, $key, 0, $iv);

    return $decrypted !== false ? $decrypted : null;
}

function getDefaultCurrencySymbol(): string
{
    $defaultCurrency = GeneralSetting::where('key', 'currency_symbol')->first();
    $currencyId = $defaultCurrency->value ?? '';
    if ($currencyId) {
        $currency = Currency::find((string) $currencyId);
        if ($currency instanceof Currency) {
            return $currency->symbol ?? '$';
        }
    }
    return '$';
}

function isRTL(?string $languageCode = null): int|string
{
    $language = TranslationLanguage::select('id')->where('code', $languageCode)->first();
    if ($language) {
        $languageId = $language->id;
        $language = Language::select('rtl')->where('language_id', $languageId)->first();
        if ($language && isset($language->rtl)) {
            return $language->rtl;
        }
    }
    return 0;
}

if (! function_exists('formatFileSize')) {
    function formatFileSize(int|string $bytes): string
    {
        $bytes = (int) $bytes;
        if ($bytes === 0) {
            return '0 B';
        }

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor(log($bytes, 1024));
        return sprintf('%.2f', $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
    }
}

if (! function_exists('getUserPermissions')) {
    /**
     * @return Collection<int, Permission>
     */
    function getUserPermissions(int|string|null $userId = null): Collection
    {
        $user = $userId ? User::find($userId) : current_user();

        if (! $user || empty($user->role_id)) {
            return collect();
        }

        $cacheKey = 'permissions_' . $user->role_id;

        return Cache::remember($cacheKey, 86400, function () use ($user) {
            return Permission::where('permissions.role_id', $user->role_id)
                ->whereHas('role', function ($query) {
                    $query->where('status', 1);
                })
                ->select(
                    'permissions.module_id',
                    'permissions.create',
                    'permissions.edit',
                    'permissions.view',
                    'permissions.delete',
                    'permissions.allow_all'
                )
                ->with(['module:id,module_slug'])
                ->get();
        });
    }
}

/**
 * @param Collection<int, \Modules\RolesPermission\Models\Permission> $permissions
 * @param string|array<string> $moduleSlug
 * @param string $action
 *
 * @return bool
 */
function hasPermission(Collection $permissions, string|array $moduleSlug, string $action): bool
{
    $user = current_user();

    $userType = $user->user_type ?? '';
    if ($userType === 1) {
        return true;
    }

    $moduleSlugs = is_array($moduleSlug) ? $moduleSlug : [$moduleSlug];

    foreach ($moduleSlugs as $moduleSlug) {
        $permission = $permissions->firstWhere(function ($perm) use ($moduleSlug) {
            return $perm->module && $perm->module->module_slug === $moduleSlug;
        });
        if ($permission && isset($permission->$action) && $permission->$action === 1) {
            return true;
        }
    }

    return false;
}

function current_user(?string $guard = null): ?Authenticatable
{
    $guard = $guard ?? Auth::getDefaultDriver();

    return Auth::guard($guard)->check()
        ? Auth::guard($guard)->user()
        : null;
}

function gigNotificationEnabled(): bool
{
    $bookingNotification = GeneralSetting::where('group_id', 2)->where('key', 'bookingUpdates')->first();
    if ($bookingNotification) {
        return $bookingNotification->value === '1';
    }
    return false;
}

/**
 * @param array<string, mixed> $notifyData
 */
function sendNotification(string $email, string $slug, array $notifyData = []): bool
{
    $success = false;
    try {
        $notificationType = NotificationType::where('slug', $slug)->first();
        if (! $notificationType) {
            return false;
        }
        $placeholders = json_decode($notificationType->tags ?? '', true);
        $template = EmailTemplate::where('notification_type', $notificationType->id)
            ->where('status', 1)
            ->first();
        if (! $template || ! $email) {
            return false;
        }

        $replaced = function ($text) use ($placeholders, $notifyData) {
            if (! $placeholders || ! is_array($placeholders)) {
                return $text;
            }

            foreach ($placeholders as $tag) {
                $search = '{' . $tag . '}';
                $replace = $notifyData[$tag] ?? '';
                $text = str_replace($search, $replace, $text);
            }

            return $text;
        };

        $parsedTemplate = [
            'subject' => $replaced($template->subject),
            'content' => $replaced($template->description),
            'sms_content' => $replaced($template->sms_content),
            'notification_content' => $replaced($template->notification_content),
        ];

        if (! empty($parsedTemplate['subject'])) {
            $payload = [
                'to_email' => $email,
                'subject' => $parsedTemplate['subject'],
                'content' => $parsedTemplate['content'],
            ];
            $emailPayload = new Request($payload);
            $emailController = new EmailController();
            $emailController->sendEmail($emailPayload);
            $user = User::where('email', $email)->first();
            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'related_user_id' => $notifyData['related_user_id'] ?? null,
                    'subject' => $parsedTemplate['subject'],
                    'content' => $parsedTemplate['notification_content'],
                ]);
            }
        }
        $success = true;
    } catch (\Exception $e) {
        $success = false;
    }

    return $success;
}

function getLanguageId(string $langCode = 'en'): int
{
    $languageId = TranslationLanguage::where('code', $langCode)->value('id');
    return $languageId ?? 1;
}

function isAccessMenu(string $menu): int
{
    $value = 0;
    if ($menu === 'reservation') {
        $value = GeneralSetting::where(['group_id' => 20, 'key' => 'reservation'])->value('value') ?? 0;
    }
    if ($value) {
        return $value;
    }
    return 0;
}

function getCurrentUserImage(): string
{
    /** @var \App\Models\User $user */
    $user = current_user();

    return $user->userDetail
        ? $user->userDetail->profile_image
        : url('backend/assets/img/default-profile.png');
}

function getUserProfileImage(int $userId): string
{
    $user = User::where('id', $userId)->first();
    return $user->userDetail
        ? $user->userDetail->profile_image
        : url('backend/assets/img/default-profile.png');
}
function getCurrentUserFullname(?int $userId = null): string
{
    if ($userId) {
        $user = User::where('id', $userId)->first();
        $userDetails = UserDetail::where('user_id', $userId)->first();

        $fullName = $user ? ($user->name ?? '') : '';

        if ($userDetails && $userDetails->first_name && $userDetails->last_name) {
            $fullName = trim(($userDetails->first_name ?? '') . ' ' . ($userDetails->last_name ?? ''));
        }

        return $fullName;
    }

    /** @var \App\Models\User|null $user */
    $user = current_user();

    if ($user && $user->userDetail && $user->userDetail->first_name) {
        return trim(($user->userDetail->first_name ?? '') . ' ' . ($user->userDetail->last_name ?? ''));
    }

    return $user ? ($user->name ?? '') : '';
}

/**
 * Send a notification to the given email based on the provided slug and data.
 *
 * @param string $slug
 * @param string|array<string> $email
 * @param array<string, mixed> $notifyData
 *
 * @return void
 */
function sendNewsletterEmail(string|array $email, string $slug, array $notifyData): void
{
    $notificationType = NotificationType::where('slug', $slug)->first();
    if (! $notificationType) {
        return;
    }
    $placeholders = json_decode($notificationType->getAttribute('tags'), true);
    $template = EmailTemplate::where('notification_type', $notificationType->id)
        ->where('status', 1)
        ->first();

    $notifyData = getCommonSettingData($notifyData);

    $replaced = function ($text) use ($placeholders, $notifyData) {
        if (! $placeholders || ! is_array($placeholders)) {
            return $text;
        }

        foreach ($placeholders as $tag) {
            $search = '{' . $tag . '}';
            $replace = $notifyData[$tag] ?? '';
            $text = str_replace($search, $replace, $text);
        }

        return $text;
    };

    if (! $email) {
        return;
    }

    $subject = $template->subject ?? 'Reg - Newsletter';
    $content = $template->description ?? 'You have successfully subscribed to our newsletter.';

    if ($slug === 'test_mail') {
        $subject = $template->subject ?? 'Reg - Admin Test Mail';
        $content = $template->description ?? "Hello {$notifyData['user_name']},<br><br>
        This is a test email to confirm that the email configuration for admin notifications is working correctly.<br><br>
        If you have received this email, everything is set up properly on your end. No further action is required.<br><br>
        Regards,<br>
        System Administrator";
    }

    $parsedTemplate = [
        'subject' => $replaced($subject),
        'content' => $replaced($content),
    ];

    $payload = [
        'to_email' => $email,
        'subject' => $parsedTemplate['subject'],
        'content' => $parsedTemplate['content'],
    ];

    $emailPayload = new Request($payload);
    $emailController = new EmailController();
    $emailController->sendEmail($emailPayload);
}

/**
 * @param array<string, mixed>|null $notifyData
 *
 * @return array<string, mixed>
 */
function getCommonSettingData(?array $notifyData): array
{
    $generalData = GeneralSetting::where('group_id', 1)->pluck('value', 'key');

    $notifyData = $notifyData ?? [];

    foreach ($generalData as $key => $value) {
        if ($key === 'organization_name') {
            $notifyData['company_name'] = $value;
        }
        if ($key === 'company_email') {
            $notifyData['company_email'] = $value;
        }
        if ($key === 'company_phone') {
            $notifyData['company_phone'] = $value;
        }
        if ($key === 'company_address_line') {
            $notifyData['company_address'] = $value;
        }
        if ($key === 'company_postal_code') {
            $notifyData['company_postal_code'] = $value;
        }
    }

    return $notifyData;
}

function formatPrice(float|int $price, bool $withSymbol = true, bool $rawPrice = false): string|float|int
{
    $defaultCurrencySymbol = getDefaultCurrencySymbol();

    $currencyPosition = GeneralSetting::where('key', 'currency_position')->first();
    $currencyPosition = $currencyPosition ? $currencyPosition->value : 'before';

    if ($rawPrice) {
        return $price;
    }

    $formattedPrice = number_format($price, 2, '.', ',');

    if ($withSymbol) {
        return $currencyPosition === 'before'
            ? $defaultCurrencySymbol . $formattedPrice
            : $formattedPrice . $defaultCurrencySymbol;
    }

    return $formattedPrice;
}

if (! function_exists('getBaseUrl')) {
    function getBaseUrl(): string
    {
        if (app()->runningInConsole()) {
            return config('app.url'); // fallback for CLI
        }

        return request()->getSchemeAndHttpHost();
    }
}

if (! function_exists('getUserWalletBalance')) {
    /**
     * @param int $userId
     *
     * @return array<string, float>
     */
    function getUserWalletBalance(int $userId): array
    {
        $totalCredit = (float) \App\Models\WalletHistory::where('user_id', $userId)
            ->where('status', 'Completed')
            ->where('type', '1')
            ->sum('amount');

        $totalDebit = (float) \App\Models\WalletHistory::where('user_id', $userId)
            ->where('status', 'Completed')
            ->where('type', '2')
            ->sum('amount');

        return [
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
            'total_balance' => $totalCredit - $totalDebit,
        ];
    }
}

function getGeneralSetting(string $key, mixed $default = null): mixed
{
    return GeneralSetting::where('key', $key)->value('value') ?? $default;
}

function reviewExists(?int $gigsId = null, ?int $userId = null): bool
{
    if (is_null($userId)) {
        /** @var \App\Models\User|null $user */
        $user = current_user();
        $userId = $user?->id;
    }

    return Review::where('gigs_id', $gigsId)
        ->where('parent_id', 0)
        ->where('user_id', $userId)
        ->exists();
}

function isBookingCompleted(?int $gigId = null, ?int $userId = null): bool
{
    return Booking::where('gigs_id', $gigId)->where('customer_id', $userId)->where('booking_status', Booking::$completed)->exists();
}

function gigOwnerUserId(?int $gigId = null): ?int
{
    return Gigs::where('id', $gigId)->value('user_id');
}

if (!function_exists('buildResponse')) {
    function buildResponse(int $code, bool $success, string $message, $data = null, $error = null): array
    {
        return array_filter([
            'code' => $code,
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], fn($value) => !is_null($value));
    }
}
