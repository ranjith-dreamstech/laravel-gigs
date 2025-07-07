<?php

namespace Modules\GeneralSetting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\GeneralSetting\Http\Requests\UpdateAdminProfileRequest;
use Modules\GeneralSetting\Repositories\Contracts\AdminProfileInterface;

class AdminProfileController extends Controller
{
    protected AdminProfileInterface $profileRepo;

    public function __construct(AdminProfileInterface $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

    public function adminProfile(): View
    {
        return view('generalsetting::adminProfile.index');
    }

    public function getProfile(): JsonResponse
    {
        return response()->json($this->profileRepo->getProfile());
    }

    public function updateProfile(UpdateAdminProfileRequest $request): JsonResponse
    {
        /** @var array{
         *     id: int,
         *     email: string,
         *     phone: string,
         *     first_name: string,
         *     last_name: string,
         *     profile_photo?: \Illuminate\Http\UploadedFile|null,
         *     address_line?: string|null,
         *     postal_code?: string|null,
         *     country?: int|null,
         *     state?: int|null,
         *     city?: int|null
         * } $validated
         */
        $validated = $request->validated();

        return response()->json($this->profileRepo->updateProfile($validated));
    }

    public function checkPassword(Request $request): JsonResponse
    {
        return response()->json($this->profileRepo->checkPassword($request->current_password));
    }

    public function deleteAccount(): JsonResponse
    {
        return response()->json($this->profileRepo->deleteAccount());
    }
}
