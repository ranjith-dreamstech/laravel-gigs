<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserSettingRepositoryInterface
{
    /** @return array<string, mixed> */
    public function updateSettings(Request $request): array;

    /** @return array<string, mixed> */
    public function getSettings(): array;

    /** @return array<string, mixed> */
    public function changePassword(Request $request): array;

    /** @return array<string, mixed> */
    public function deactivateAccount(): array;

    /** @return array<string, mixed> */
    public function uploadProfileImage(Request $request): array;

    /** @return array<string, mixed> */
    public function removeProfileImage(): array;

    /** @return array<string, mixed> */
    public function fetchUserProfile(): array;

    /** @return array<string, mixed> */
    public function saveAccountSettings(Request $request): array;

    /** @return array<string, mixed> */
    public function getUserDevices(): array;

    /** @return array<string, mixed> */
    public function logoutDevice(Request $request): array;
}
