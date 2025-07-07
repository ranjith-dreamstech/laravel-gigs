<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use App\Models\User;
use App\Models\UserDetail;
use App\Services\ImageResizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\GeneralSetting\Repositories\Contracts\AdminProfileInterface;

class AdminProfileRepository implements AdminProfileInterface
{
    protected ImageResizer $imageResizer;

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     data?: array<string, mixed>,
     *     error?: string
     * }
     */
    public function getProfile(): array
    {
        try {
            $user = Auth::guard('admin')->user();
            if (! $user instanceof User) {
                return [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'User not found',
                ];
            }

            $profile = [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone_number,
                'first_name' => $user->userDetail->first_name ?? null,
                'last_name' => $user->userDetail->last_name ?? null,
                'address_line' => $user->userDetail->address ?? null,
                'country' => $user->userDetail->country_id ?? null,
                'state' => $user->userDetail->state_id ?? null,
                'city' => $user->userDetail->city_id ?? null,
                'postal_code' => $user->userDetail->postal_code ?? null,
                'profile_photo' => $user->userDetail->profile_image ?? null,
            ];

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.profile_update_success'),
                'data' => $profile,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.profile_update_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

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
     *     profile_photo?: UploadedFile|null
     * } $data
     *
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function updateProfile(array $data): array
    {
        try {
            $user = User::find(Auth::guard('admin')->id());

            if (! $user instanceof User) {
                return [
                    'status' => 'error',
                    'code' => 404,
                    'message' => __('admin.general_settings.user_not_found'),
                ];
            }

            $user->update([
                'email' => $data['email'],
                'phone_number' => $data['phone'],
            ]);

            $profilePhotoPath = $user->userDetail->profile_image ?? null;

            if (! empty($data['profile_photo'])) {
                $profilePhotoPath = $this->imageResizer->uploadFile($data['profile_photo'], 'profile', $profilePhotoPath);
            }

            UserDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'address' => $data['address_line'] ?? null,
                    'country_id' => $data['country'] ?? null,
                    'state_id' => $data['state'] ?? null,
                    'city_id' => $data['city'] ?? null,
                    'postal_code' => $data['postal_code'] ?? null,
                    'profile_image' => $profilePhotoPath,
                ]
            );

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.profile_update_success'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.profile_update_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     valid: bool,
     *     error?: string
     * }
     */
    public function checkPassword(string $currentPassword): array
    {
        $response = [
            'status' => 'error',
            'code' => 500,
            'valid' => false,
            'error' => __('admin.general_settings.profile_update_error'),
        ];

        try {
            $user = Auth::guard('admin')->user();

            if (! $user instanceof User) {
                $response['code'] = 404;
                $response['error'] = 'User not found';
                return $response;
            }

            if ($user->password === null) {
                $response['code'] = 400;
                $response['error'] = 'No password set for this user';
                return $response;
            }

            $response = [
                'status' => 'success',
                'code' => 200,
                'valid' => Hash::check($currentPassword, $user->password),
            ];
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    /**
     * @return array{
     *     status: string,
     *     code: int,
     *     message: string,
     *     error?: string
     * }
     */
    public function deleteAccount(): array
    {
        try {
            $user = Auth::guard('admin')->user();

            if (! $user instanceof User) {
                return [
                    'status' => 'error',
                    'code' => 404,
                    'message' => __('admin.general_settings.user_not_found'),
                ];
            }

            $user->delete();

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.account_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.profile_update_error'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
