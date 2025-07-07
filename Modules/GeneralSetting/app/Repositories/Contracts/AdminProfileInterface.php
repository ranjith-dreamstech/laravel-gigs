<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

interface AdminProfileInterface
{
    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     data?: array<string, mixed>,
     *     error?: string
     * }
     */
    public function getProfile(): array;

    /**
     * @param array{
     *     email: string,
     *     phone: string,
     *     first_name: string,
     *     last_name: string,
     *     address_line?: string|null,
     *     country?: int|null,
     *     state?: int|null,
     *     city?: int|null,
     *     postal_code?: string|null,
     *     profile_photo?: \Illuminate\Http\UploadedFile|null
     * } $data
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function updateProfile(array $data): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     valid: bool,
     *     error?: string
     * }
     */
    public function checkPassword(string $currentPassword): array;

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function deleteAccount(): array;
}
