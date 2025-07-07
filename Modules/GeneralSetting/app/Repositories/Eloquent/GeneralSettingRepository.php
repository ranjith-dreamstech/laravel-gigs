<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use App\Exceptions\CustomException;
use App\Services\ImageResizer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Models\UserDevice;
use Modules\GeneralSetting\Repositories\Contracts\GeneralSettingInterface;

class GeneralSettingRepository implements GeneralSettingInterface
{
    protected ImageResizer $imageResizer;
    /** @var array<int, string> */
    protected array $imageKeys = [
        'logo_image',
        'favicon_image',
        'small_image',
        'dark_logo',
        'invoice_logo',
        'maintenance_image',
        'metaImage',
    ];

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \Modules\GeneralSetting\Models\GeneralSetting>
     */
    public function getSettingsByGroup(int $groupId)
    {
        return GeneralSetting::where('group_id', $groupId)->get()->map(function ($setting) {
            if (in_array($setting->key, $this->imageKeys)) {
                $setting->value = uploadedAsset($setting->value, 'default2');
            }
            return $setting;
        });
    }

    /**
     * @param array{
     *     company_profile_photo?: \Illuminate\Http\UploadedFile|null,
     *     group_id?: int,
     *     organization_name?: string|null,
     *     owner_name?: string|null,
     *     company_email?: string|null,
     *     international_phone_number?: string|null,
     *     industry?: string|null,
     *     team_size?: string|null,
     *     company_address_line?: string|null,
     *     country?: string|null,
     *     state?: string|null,
     *     city?: string|null,
     *     company_postal_code?: string|null,
     *     ...<string, mixed>
     * } $data
     */
    public function storeCompanySettings(array $data): void
    {
        $file = $data['company_profile_photo'] ?? null;
        unset($data['company_profile_photo']);

        if ($file !== null) {
            $companyPhotoPath = 'company';
            $companyPhotoStoragePath = $this->imageResizer->uploadFile($file, 'company', $companyPhotoPath);

            GeneralSetting::updateOrCreate(
                ['key' => 'company_profile_photo'],
                [
                    'value' => $companyPhotoStoragePath,
                    'group_id' => $data['group_id'] ?? null,
                ]
            );
        }

        // Save other general settings
        foreach ($data as $key => $value) {
            if ($value !== null) {
                GeneralSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group_id' => $data['group_id'] ?? null,
                    ]
                );
            }
        }
    }

    /**
     * @return array{
     *     organization_name: string|null,
     *     owner_name: string|null,
     *     company_email: string|null,
     *     company_phone: string|null,
     *     industry: string|null,
     *     team_size: string|null,
     *     company_address_line: string|null,
     *     country: string|null,
     *     state: string|null,
     *     city: string|null,
     *     company_postal_code: string|null,
     *     company_profile_photo: string|null
     * }|null
     */
    public function getCompanySettings(int $groupId): array|null
    {
        /** @var \Illuminate\Support\Collection<string, mixed> $settings */
        $settings = GeneralSetting::where('group_id', $groupId)->pluck('value', 'key');

        if ($settings->isEmpty()) {
            return null;
        }

        return [
            'organization_name' => $settings['organization_name'] ?? null,
            'owner_name' => $settings['owner_name'] ?? null,
            'company_email' => $settings['company_email'] ?? null,
            'company_phone' => $settings['international_phone_number'] ?? null,
            'industry' => $settings['industry'] ?? null,
            'team_size' => $settings['team_size'] ?? null,
            'company_address_line' => $settings['company_address_line'] ?? null,
            'country' => $settings['country'] ?? null,
            'state' => $settings['state'] ?? null,
            'city' => $settings['city'] ?? null,
            'company_postal_code' => $settings['company_postal_code'] ?? null,
            'company_profile_photo' => uploadedAsset($settings['company_profile_photo'] ?? null, 'default'),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function saveNotificationSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'group_id') {
                continue;
            }

            GeneralSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group_id' => $data['group_id'],
                ]
            );
        }
    }

    /**
     * Update prefix settings
     *
     * @param array<string, string> $settings
     * @param int $groupId
     *
     * @return void
     *
     * @throws \Exception
     */
    public function updatePrefixes(array $settings, int $groupId): void
    {
        foreach ($settings as $key => $value) {
            if ($key !== 'group_id') {
                GeneralSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group_id' => $groupId,
                    ]
                );
            }
        }
    }

    /**
     * Get all prefix settings for a group
     *
     * @param int $groupId
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Modules\GeneralSetting\Models\GeneralSetting>
     */
    public function getPrefixesByGroup(int $groupId): \Illuminate\Database\Eloquent\Collection
    {
        return GeneralSetting::where('group_id', $groupId)
            ->whereIn('key', [
                'reservation_prefix',
                'quotation_prefix',
                'enquiry_prefix',
                'company_prefix',
                'inspection_prefix',
                'report_prefix',
                'customer_prefix',
            ])
            ->get();
    }

    /**
     * @param array{
     *     metaImage?: \Illuminate\Http\UploadedFile|null,
     *     metaTitle?: string|null,
     *     metaDescription?: string|null,
     *     metaKeywords?: string|null,
     *     _token?: string,
     *     ...<string, mixed>
     * } $data
     * @param int|null $groupId
     */
    public function storeSeoSettings(array $data, ?int $groupId = 6): void
    {
        $file = $data['metaImage'] ?? null;
        unset($data['metaImage'], $data['_token']);

        $existing = GeneralSetting::where('key', 'metaImage')->first();
        $oldPath = $existing?->value;

        if ($file !== null) {
            $seoPhotoPath = 'seo';
            $seoPhotoStoragePath = $this->imageResizer->uploadFile($file, $seoPhotoPath, $oldPath);

            GeneralSetting::updateOrCreate(
                ['key' => 'metaImage'],
                [
                    'value' => $seoPhotoStoragePath,
                    'group_id' => $groupId,
                ]
            );
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                GeneralSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group_id' => $groupId,
                    ]
                );
            }
        }

        Cache::forget('seo_settings');
    }

    /**
     * @param array<string, \Illuminate\Http\UploadedFile> $files
     *
     * @return array<string, string>
     */
    public function storeLogoSettings(array $files, int $groupId = 16): array
    {
        $paths = [];
        $logoFields = [
            'logo_image' => 'logo',
            'favicon_image' => 'favicon',
            'small_image' => 'small',
            'dark_logo' => 'dark',
        ];

        foreach ($logoFields as $field => $folderName) {
            if (isset($files[$field])) {
                $file = $files[$field];
                $existing = GeneralSetting::where('key', $field)->first();
                $oldPath = $existing?->value;

                $relativePath = $this->imageResizer->uploadFile($file, 'logo', $oldPath);

                $storagePath = $relativePath ?? '';

                GeneralSetting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $storagePath, 'group_id' => $groupId]
                );

                $paths[$field] = $storagePath;
            }
        }

        return $paths;
    }

    /**
     * @param array{
     *     group_id: int,
     *     maintenance_image?: \Illuminate\Http\UploadedFile|null,
     *     is_remove_image?: bool|string,
     *     _token?: string,
     *     ...<string, mixed>
     * } $data
     */
    public function storeMaintenanceSettings(array $data): void
    {
        try {
            $groupId = $data['group_id'];
            $file = $data['maintenance_image'] ?? null;
            $isRemove = filter_var($data['is_remove_image'] ?? false, FILTER_VALIDATE_BOOLEAN);

            foreach (['_token', 'maintenance_image', 'is_remove_image'] as $key) {
                if (array_key_exists($key, $data)) {
                    unset($data[$key]);
                }
            }

            if ($file !== null) {
                $existing = GeneralSetting::where('key', 'maintenance_image')->first();
                $oldPath = $existing?->value;

                $relativePath = $this->imageResizer->uploadFile($file, 'maintenance', $oldPath);
                GeneralSetting::updateOrCreate(
                    ['key' => 'maintenance_image'],
                    ['value' => $relativePath, 'group_id' => $groupId]
                );
            }

            // Remove image if requested
            if ($isRemove) {
                $existing = GeneralSetting::where('key', 'maintenance_image')->first();
                if ($existing && $existing->value) {
                    $paths = [
                        storage_path('app/public/' . $existing->value),
                        storage_path('app/public/' . str_replace('maintenance/', 'maintenance/thumbnail/', $existing->value)),
                    ];
                    foreach ($paths as $path) {
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                    }
                    $existing->update(['value' => '']);
                }
            }

            // Save other settings
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    GeneralSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'group_id' => $groupId]
                    );
                }
            }
        } catch (Exception $e) {
            \Log::error('Maintenance settings update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateThemeSettings(array $data): void
    {
        try {
            $groupId = $data['group_id'];

            foreach ($data as $key => $value) {
                if ($key !== 'group_id') {
                    GeneralSetting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'group_id' => $groupId,
                        ]
                    );
                }
            }
        } catch (Exception $e) {
            \Log::error('Theme settings update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array{
     *     otp_type: string,
     *     otp_digit_limit: int,
     *     otp_expire_time: int,
     *     login?: bool,
     *     register?: bool
     * } $data
     */
    public function storeOtpSettings(array $data): void
    {
        try {
            $settings = [
                'otp_type' => $data['otp_type'],
                'otp_digit_limit' => $data['otp_digit_limit'],
                'otp_expire_time' => $data['otp_expire_time'],
                'login' => $data['login'] ?? false,
                'register' => $data['register'] ?? false,
            ];

            foreach ($settings as $key => $value) {
                GeneralSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        } catch (Exception $e) {
            \Log::error('Failed to store OTP settings: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array{
     *     language: string|int,
     *     group_id: int,
     *     copy_right_description: string
     * } $data
     */
    public function updateCopyright(array $data): void
    {
        GeneralSetting::updateOrCreate(
            [
                'key' => 'copy_right_' . $data['language'],
                'group_id' => $data['group_id'],
            ],
            [
                'value' => $data['copy_right_description'],
                'language_id' => $data['language'],
            ]
        );
    }

    /**
     * @param array{
     *     language_id?: int,
     *     group_id: int
     * } $data
     *
     * @return \Modules\GeneralSetting\Models\GeneralSetting|null
     */
    public function getCopyright(array $data): ?\Modules\GeneralSetting\Models\GeneralSetting
    {
        $languageId = $data['language_id'] ?? Language::where('default', 1)->value('language_id');

        return GeneralSetting::where('group_id', $data['group_id'])
            ->where('key', 'copy_right_' . $languageId)
            ->first();
    }

    /**
     * @param array{
     *     minAdvanceReservation?: int|null,
     *     maxAdvanceReservation?: int|null,
     *     cancellationBuffer?: int|null,
     *     rescheduleBuffer?: int|null,
     *     faq?: string|null,
     *     damages?: string|null,
     *     extraService?: string|null,
     *     booking?: string|null,
     *     enquiries?: string|null,
     *     reservation?: string|null,
     *     seasonalPricing?: string|null,
     *     pricing?: string|null
     * } $data
     */
    public function saveRentalSettings(array $data): void
    {
        $settings = [
            'minAdvanceReservation' => $data['minAdvanceReservation'] ?? null,
            'maxAdvanceReservation' => $data['maxAdvanceReservation'] ?? null,
            'cancellationBuffer' => $data['cancellationBuffer'] ?? null,
            'rescheduleBuffer' => $data['rescheduleBuffer'] ?? null,
            'faq' => $data['faq'] ?? null,
            'damages' => $data['damages'] ?? null,
            'extraService' => $data['extraService'] ?? null,
            'booking' => $data['booking'] ?? null,
            'enquiries' => $data['enquiries'] ?? null,
            'reservation' => $data['reservation'] ?? null,
            'seasonalPricing' => $data['seasonalPricing'] ?? null,
            'pricing' => $data['pricing'] ?? null,
        ];

        foreach ($settings as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * @param array{
     *     group_id?: int,
     *     invoice_logo?: \Illuminate\Http\UploadedFile|null,
     *     is_remove_image?: bool|string,
     *     invoice_prefix?: string|null,
     *     invoice_due?: int|null,
     *     invoice_round_off?: int|null,
     *     round_off_enabled?: string,
     *     show_company_details?: string,
     *     invoice_terms?: string|null,
     *     _token?: string
     * } $data
     */
    public function saveInvoiceSettings(array $data): void
    {
        try {
            $groupId = $data['group_id'] ?? 9;
            $file = $data['invoice_logo'] ?? null;
            $isRemove = $data['is_remove_image'] ?? false;

            // Safely unset optional keys
            foreach (['_token', 'invoice_logo', 'is_remove_image'] as $key) {
                if (array_key_exists($key, $data)) {
                    unset($data[$key]);
                }
            }

            // Handle invoice logo upload
            if ($file !== null) {
                $existing = GeneralSetting::where('key', 'invoice_logo')->first();
                $oldPath = $existing->value ?? null;

                // Upload new file and get relative path
                $relativePath = $this->imageResizer->uploadFile(
                    $file,
                    'invoices',
                    $oldPath
                );

                GeneralSetting::updateOrCreate(
                    ['key' => 'invoice_logo'],
                    ['value' => $relativePath, 'group_id' => $groupId]
                );
            }

            // Remove image if requested
            if (filter_var($isRemove, FILTER_VALIDATE_BOOLEAN)) {
                $existing = GeneralSetting::where('key', 'invoice_logo')->first();
                if ($existing && $existing->value) {
                    $paths = [
                        storage_path('app/public/' . $existing->value),
                        storage_path('app/public/' . str_replace('invoices/', 'invoices/thumbnail/', $existing->value)),
                    ];
                    foreach ($paths as $path) {
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                    }
                    $existing->update(['value' => '']);
                }
            }

            $settings = [
                'invoice_prefix' => $data['invoice_prefix'] ?? null,
                'invoice_due' => $data['invoice_due'] ?? null,
                'invoice_round_off' => $data['invoice_round_off'] ?? null,
                'round_off_enabled' => ($data['round_off_enabled'] ?? 'off') === 'on' ? 1 : 0,
                'show_company_details' => ($data['show_company_details'] ?? 'off') === 'on' ? 1 : 0,
                'invoice_terms' => $data['invoice_terms'] ?? null,
            ];

            foreach ($settings as $key => $value) {
                if ($value !== null) {
                    GeneralSetting::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'group_id' => $groupId]
                    );
                }
            }
        } catch (Exception $e) {
            \Log::error('Invoice settings update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getCookiesSettings(int $groupId, ?int $languageId = null): array
    {
        if (! $languageId) {
            $defaultLanguage = Language::where('default', 1)->first();

            if (! $defaultLanguage) {
                throw new CustomException(__('admin.general_settings.language_not_found'));
            }

            $languageId = $defaultLanguage->language_id;
        }

        $keys = [
            'cookiesContentText',
            'cookiesPosition',
            'agreeButtonText',
            'declineButtonText',
            'showDeclineButton',
            'cookiesPageLink',
        ];

        /** @var \Illuminate\Support\Collection<string, mixed> $settings */
        $settings = GeneralSetting::where('group_id', $groupId)
            ->whereIn('key', array_map(fn ($key) => $key . '_' . $languageId, $keys))
            ->pluck('value', 'key');

        $formatted = [];
        foreach ($settings as $key => $value) {
            $baseKey = explode('_' . $languageId, $key)[0];
            $formatted[$baseKey] = $value;
        }

        return $formatted;
    }

    /**
     * @param array{
     *     cookiesContentText: string,
     *     cookiesPosition: string,
     *     agreeButtonText: string,
     *     declineButtonText: string,
     *     showDeclineButton?: mixed,
     *     cookiesPageLink: string,
     *     language: string|int,
     *     group_id: int
     * } $data
     */
    public function storeCookiesSettings(array $data): void
    {
        $fields = [
            'cookiesContentText' => $data['cookiesContentText'],
            'cookiesPosition' => $data['cookiesPosition'],
            'agreeButtonText' => $data['agreeButtonText'],
            'declineButtonText' => $data['declineButtonText'],
            'showDeclineButton' => isset($data['showDeclineButton']) ? 1 : 0,
            'cookiesPageLink' => $data['cookiesPageLink'],
        ];

        foreach ($fields as $key => $value) {
            GeneralSetting::updateOrCreate(
                [
                    'key' => $key . '_' . $data['language'],
                    'group_id' => $data['group_id'],
                ],
                [
                    'value' => $value,
                    'language_id' => $data['language'],
                ]
            );
        }
    }

    /**
     * @param array{
     *     current_password: string,
     *     new_password: string
     * } $data
     *
     * @return array{
     *     success: bool,
     *     message: string
     * }
     */
    public function updatePassword(array $data): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('admin')->user();

        if (! $user instanceof \App\Models\User) {
            return [
                'success' => false,
                'message' => __('admin.general_settings.user_not_found'),
            ];
        }

        if (! Hash::check($data['current_password'], $user->password ?? '')) {
            return [
                'success' => false,
                'message' => __('admin.general_settings.current_password_incorrect'),
            ];
        }

        $user->password = Hash::make($data['new_password']);
        $user->last_password_changed_at = now();
        $user->save();

        return [
            'success' => true,
            'message' => __('admin.general_settings.password_updated_successfully'),
        ];
    }

    /**
     * @param array{
     *     phone_current_password: string,
     *     current_phonenumber: string,
     *     new_phonenumber: string
     * } $data
     *
     * @return array{
     *     success: bool,
     *     message: string
     * }
     */
    public function updatePhoneNumber(array $data): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('admin')->user();

        $response = [
            'success' => false,
            'message' => __('admin.general_settings.user_not_found'),
        ];

        if (! $user instanceof \App\Models\User) {
            return $response;
        }

        if (! Hash::check($data['phone_current_password'], $user->password ?? '')) {
            $response['message'] = __('admin.general_settings.current_password_incorrect');
        } elseif ($user->phone_number !== $data['current_phonenumber']) {
            $response['message'] = __('admin.general_settings.phone_number_incorrect');
        } else {
            $user->phone_number = $data['new_phonenumber'];
            $user->save();

            $response['success'] = true;
            $response['message'] = __('admin.general_settings.phone_number_updated_successfully');
        }

        return $response;
    }


    /**
     * @param array{
     *     email_current_password: string,
     *     current_email: string,
     *     new_email: string
     * } $data
     *
     * @return array{
     *     success: bool,
     *     message: string
     * }
     */
    public function updateEmail(array $data): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('admin')->user();

        $response = [
            'success' => false,
            'message' => __('admin.general_settings.user_not_found'),
        ];

        if (! $user instanceof \App\Models\User) {
            return $response;
        }

        if (! Hash::check($data['email_current_password'], $user->password ?? '')) {
            $response['message'] = __('admin.general_settings.current_password_incorrect');
        } elseif ($user->email !== $data['current_email']) {
            $response['message'] = __('admin.general_settings.current_email_incorrect');
        } else {
            $user->email = $data['new_email'];
            $user->save();

            $response['success'] = true;
            $response['message'] = __('admin.general_settings.email_updated_successfully');
        }

        return $response;
    }


    /**
     * @return array{
     *     user: \App\Models\User|null,
     *     last_password_changed_at: string,
     *     devices: \Illuminate\Support\Collection<int, array{
     *         id: int,
     *         device_type: string|null,
     *         browser: string|null,
     *         os: string|null,
     *         ip_address: string|null,
     *         location: string|null,
     *         date: string
     *     }>
     * }
     */
    public function getSecuritySettings(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('admin')->user();

        $devices = UserDevice::where('user_id', $user?->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(fn ($device) => [
                'id' => $device->id,
                'device_type' => $device->device_type ?? null,
                'browser' => $device->browser ?? null,
                'os' => $device->os ?? null,
                'ip_address' => $device->ip_address ?? null,
                'location' => $device->location ?? null,
                'date' => formatDateTime($device->created_at),
            ]);

        return [
            'user' => $user,
            'last_password_changed_at' => $user?->last_password_changed_at ? formatDateTime($user->last_password_changed_at) : 'Never',
            'devices' => $devices,
        ];
    }

    /**
     * @param array{
     *     isAll: string,
     *     id?: int
     * } $data
     *
     * @return array{
     *     success: bool,
     *     message: string
     * }
     */
    public function logoutDevice(array $data): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('admin')->user();

        if ($user === null) {
            return [
                'success' => false,
                'message' => __('admin.general_settings.user_not_found'),
            ];
        }

        if ($data['isAll'] === 'true') {
            UserDevice::where('user_id', $user->id)->delete();
            Auth::guard('admin')->logout();

            return [
                'success' => true,
                'message' => __('admin.general_settings.all_device_removed_successfully'),
            ];
        }

        $response = [
            'success' => false,
            'message' => __('admin.general_settings.device_id_required'),
        ];

        if (isset($data['id'])) {
            $device = UserDevice::find($data['id']);
            if ($device instanceof UserDevice) {
                $device->delete();
                $response = [
                    'success' => true,
                    'message' => __('admin.general_settings.device_removed_successfully'),
                ];
            }
        }

        return $response;
    }


    /**
     * @param array{
     *     group_id: int,
     *     _token?: string,
     *     paypal_key?: string,
     *     paypal_secret?: string,
     *     stripe_key?: string,
     *     stripe_secret?: string,
     *     ...<string, mixed>
     * } $data
     */
    public function updatePaymentSettings(array $data): bool
    {
        try {
            $group_id = $data['group_id'];
            $envUpdates = [];
            unset($data['_token']);

            foreach ($data as $key => $value) {
                if ($key !== 'group_id') {
                    $this->updateOrCreateSettingPayment(
                        ['key' => $key, 'group_id' => $group_id],
                        ['value' => $value]
                    );

                    // Track environment variable updates
                    switch ($key) {
                        case 'paypal_key':
                            $envUpdates['PAYPAL_SANDBOX_CLIENT_ID'] = (string) $value;
                            break;
                        case 'paypal_secret':
                            $envUpdates['PAYPAL_SANDBOX_CLIENT_SECRET'] = (string) $value;
                            break;
                        case 'stripe_key':
                            $envUpdates['STRIPE_KEY'] = (string) $value;
                            break;
                        case 'stripe_secret':
                            $envUpdates['STRIPE_SECRET'] = (string) $value;
                            break;
                        default:
                            break;
                    }
                }
            }

            if (! empty($envUpdates)) {
                $this->updateEnvVariables($envUpdates);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array{
     *     key: string,
     *     group_id: int,
     *     value: mixed
     * } $data
     */
    public function updatePaymentStatus(array $data): bool
    {
        return $this->updateOrCreateSettingPayment(
            ['key' => $data['key'], 'group_id' => $data['group_id']],
            ['value' => $data['value']]
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPaymentSettings(int $groupId, string $orderBy = 'desc'): array
    {
        return GeneralSetting::where('group_id', $groupId)
            ->orderBy('id', $orderBy)
            ->get()
            ->toArray();
    }

    /**
     * @param array<string, string> $envData
     */
    public function updateEnvVariables(array $envData): bool
    {
        $path = base_path('.env');

        if (! file_exists($path)) {
            return false;
        }

        $envContent = file_get_contents($path);
        if (! is_string($envContent)) {
            return false;
        }

        foreach ($envData as $key => $value) {
            $pattern = "/^{$key}=.*/m";

            if (preg_match($pattern, $envContent) === 1) {
                $replaced = preg_replace($pattern, "{$key}={$value}", $envContent);
                if (is_string($replaced)) {
                    $envContent = $replaced;
                }
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        return file_put_contents($path, $envContent) !== false;
    }

    public function updateStorageStatus(string $storageType, bool $status): bool
    {
        try {
            $oppositeStorageType = $storageType === 'local_storage' ? 'aws_storage' : 'local_storage';
            $oppositeStatus = ! $status;

            $this->updateOrCreateStorageSetting(
                ['key' => $storageType],
                ['value' => $status, 'group_id' => 8]
            );

            $this->updateOrCreateStorageSetting(
                ['key' => $oppositeStorageType],
                ['value' => $oppositeStatus, 'group_id' => 8]
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array<string, string|bool|int> $settings
     */
    public function updateAwsSettings(array $settings): bool
    {
        try {
            foreach ($settings as $key => $value) {
                $this->updateOrCreateStorageSetting(
                    ['key' => $key],
                    ['value' => $value, 'group_id' => 8]
                );
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     */
    public function updateOrCreateStorageSetting(array $conditions, array $data): bool
    {
        return (bool) GeneralSetting::updateOrCreate($conditions, $data);
    }

    /**
     * @param array{
     *     language: string|int,
     *     group_id: string|int,
     *     howitwork_description: string
     * } $data
     */
    public function storeHowItWorks(array $data): void
    {
        GeneralSetting::updateOrCreate(
            [
                'key' => 'how_it_works_' . $data['language'],
                'group_id' => $data['group_id'],
            ],
            [
                'value' => $data['howitwork_description'],
                'language_id' => $data['language'],
            ]
        );
    }

    /**
     * @param array{language_id?: int, group_id: int} $data
     *
     * @return \Modules\GeneralSetting\Models\GeneralSetting|null
     */
    public function getHowItWorks(array $data): ?GeneralSetting
    {
        $languageId = $data['language_id'] ?? Language::where('default', 1)->value('language_id');

        return GeneralSetting::where('group_id', $data['group_id'])
            ->where('key', 'how_it_works_' . $languageId)
            ->first();
    }

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     */
    protected function updateOrCreateSettingPayment(array $conditions, array $data): bool
    {
        return (bool) GeneralSetting::updateOrCreate($conditions, $data);
    }
}
