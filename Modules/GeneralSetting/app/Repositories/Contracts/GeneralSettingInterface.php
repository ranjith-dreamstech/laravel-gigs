<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

interface GeneralSettingInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \Modules\GeneralSetting\Models\GeneralSetting>
     */
    public function getSettingsByGroup(int $groupId);

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
    public function storeCompanySettings(array $data): void;

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
    public function getCompanySettings(int $groupId): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function saveNotificationSettings(array $data): void;

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
    public function updatePrefixes(array $settings, int $groupId);

    /**
     * Get all prefix settings for a group
     *
     * @param int $groupId
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Modules\GeneralSetting\Models\GeneralSetting>
     */
    public function getPrefixesByGroup(int $groupId);

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
    public function storeSeoSettings(array $data, ?int $groupId = 6): void;

    /**
     * @param array<string, \Illuminate\Http\UploadedFile> $files
     *
     * @return array<string, string>
     */
    public function storeLogoSettings(array $files, int $groupId = 16): array;

    /**
     * @param array{
     *     group_id: int,
     *     maintenance_image?: \Illuminate\Http\UploadedFile|null,
     *     is_remove_image?: bool|string,
     *     ...<string, mixed>
     * } $data
     */
    public function storeMaintenanceSettings(array $data): void;

    /**
     * @param array<string, mixed> $data
     */
    public function updateThemeSettings(array $data): void;

    /**
     * @param array{
     *     otp_type: string,
     *     otp_digit_limit: int,
     *     otp_expire_time: int,
     *     login?: bool,
     *     register?: bool
     * } $data
     */
    public function storeOtpSettings(array $data): void;

    /**
     * @param array{
     *     language: string|int,
     *     group_id: int,
     *     copy_right_description: string
     * } $data
     */
    public function updateCopyright(array $data): void;

    /**
     * @param array{
     *     language_id?: int,
     *     group_id: int
     * } $data
     *
     * @return \Modules\GeneralSetting\Models\GeneralSetting|null
     */
    public function getCopyright(array $data);

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
    public function saveRentalSettings(array $data): void;

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
    public function saveInvoiceSettings(array $data): void;

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
    public function storeCookiesSettings(array $data): void;
    /**
     * @return array<string, mixed>
     */
    public function getCookiesSettings(int $groupId, ?int $languageId = null): array;

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
    public function updatePassword(array $data): array;

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
    public function updatePhoneNumber(array $data): array;

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
    public function updateEmail(array $data): array;

    /**
     * @return array{
     *     user: \App\Models\User|null,
     *     last_password_changed_at: string,
     *     devices: \Illuminate\Support\Collection<int, array{
     *         id: int,
     *         device_type: string,
     *         browser: string,
     *         os: string,
     *         ip_address: string,
     *         location: string,
     *         date: string
     *     }>
     * }
     */
    public function getSecuritySettings(): array;

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
    public function logoutDevice(array $data): array;

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
    public function updatePaymentSettings(array $data): bool;

    /**
     * @param array{
     *     key: string,
     *     group_id: int,
     *     value: mixed
     * } $data
     */
    public function updatePaymentStatus(array $data): bool;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPaymentSettings(int $groupId, string $orderBy = 'desc'): array;

    /**
     * @param array<string, string> $envData
     */
    public function updateEnvVariables(array $envData): bool;

    public function updateStorageStatus(string $storageType, bool $status): bool;

    /**
     * @param array<string, string|bool|int> $settings
     */
    public function updateAwsSettings(array $settings): bool;

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $data
     */
    public function updateOrCreateStorageSetting(array $conditions, array $data): bool;

    /**
     * @param array{
     *     language: string|int,
     *     group_id: string|int,
     *     howitwork_description: string
     * } $data
     */
    public function storeHowItWorks(array $data): void;

    /**
     * @param array{language_id?: int, group_id: int} $data
     *
     * @return \Modules\GeneralSetting\Models\GeneralSetting|null
     */
    public function getHowItWorks(array $data);
}
