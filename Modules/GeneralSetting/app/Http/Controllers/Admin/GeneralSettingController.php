<?php

namespace Modules\GeneralSetting\Http\Controllers\Admin;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\GeneralSetting\Http\Requests\CompanySettingRequest;
use Modules\GeneralSetting\Http\Requests\CookiesSettingsRequest;
use Modules\GeneralSetting\Http\Requests\ListCompanyRequest;
use Modules\GeneralSetting\Http\Requests\LogoutDeviceRequest;
use Modules\GeneralSetting\Http\Requests\SettingListRequest;
use Modules\GeneralSetting\Http\Requests\StorageStatusUpdateRequest;
use Modules\GeneralSetting\Http\Requests\StoreAwsSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreCookiesSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreInvoiceSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreLogoSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreMaintenanceSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreNotificationSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreOtpSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreRentalSettingsRequest;
use Modules\GeneralSetting\Http\Requests\StoreSeoSetupRequest;
use Modules\GeneralSetting\Http\Requests\UpdateEmailRequest;
use Modules\GeneralSetting\Http\Requests\UpdatePasswordRequest;
use Modules\GeneralSetting\Http\Requests\UpdatePaymentSettingsRequest;
use Modules\GeneralSetting\Http\Requests\UpdatePaymentStatusRequest;
use Modules\GeneralSetting\Http\Requests\UpdatePhoneNumberRequest;
use Modules\GeneralSetting\Http\Requests\UpdateThemeSettingsRequest;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Repositories\Contracts\GeneralSettingInterface;
use App\Exceptions\GeneralSettingUpdateException;

class GeneralSettingController extends Controller
{
    protected GeneralSettingInterface $repository;

    public function __construct(GeneralSettingInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        /** @var view-string $view */
        $view = 'generalsetting::index';
        return view($view);
    }

    public function logoSettings(): View
    {
        return view('generalsetting::website_settings.logo-setting');
    }

    public function company(): View
    {
        return view('generalsetting::company.index');
    }

    public function notifications(): View
    {
        return view('generalsetting::notifications-setting.index');
    }

    public function prefixes(): View
    {
        return view('generalsetting::website_settings.prefixes');
    }

    public function maintenance(): View
    {
        return view('generalsetting::maintenance.index');
    }

    public function seosetup(): View
    {
        return view('generalsetting::website_settings.seosetup');
    }

    public function gdprCookies(): View
    {
        $languages = Language::with('transLang')->get();
        return view('generalsetting::system_settings.gdpr-cookies', compact('languages'));
    }

    public function storage(): View
    {
        return view('generalsetting::other_settings.storage-setting');
    }

    public function invoiceSettings(): View
    {
        return view('generalsetting::app_settings.invoice-setting');
    }

    public function otpSettings(): View
    {
        return view('generalsetting::website_settings.otp-setting');
    }

    public function rentalSettings(): View
    {
        return view('generalsetting::rental_settings.rental-settings');
    }

    public function storeRentalSettings(StoreRentalSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->saveRentalSettings($request->validated());

            return response()->json([
                'code' => 200,
                'message' => __('admin.general_settings.rental_saved_successfully'),
                'data' => [],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeLogoSettings(StoreLogoSettingsRequest $request): JsonResponse
    {
        try {
            $files = $request->only(['logo_image', 'favicon_image', 'small_image', 'dark_logo']);
            $paths = $this->repository->storeLogoSettings($files);

            return response()->json([
                'code' => 200,
                'message' => __('admin.general_settings.logo_update_success'),
                'data' => $paths,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.logo_setting_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeOtpSettings(StoreOtpSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->storeOtpSettings($request->validatedData());

            return response()->json([
                'code' => 200,
                'message' => __('admin.general_settings.otp_success'),
                'data' => [],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
            ], 500);
        }
    }

    public function storageStatusUpdate(StorageStatusUpdateRequest $request): JsonResponse
    {
        try {
            $success = $this->repository->updateStorageStatus(
                $request->storage_type,
                (bool) $request->status
            );

            if ($success) {
                $message = $request->status === 1
                    ? ucfirst(str_replace('_', ' ', $request->storage_type)) . ' activated'
                    : ucfirst(str_replace('_', ' ', $request->storage_type)) . ' blocked';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            throw new GeneralSettingUpdateException(__('admin.general_settings.update_failed'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.general_settings.retrive_error'),
            ], 500);
        }
    }

    public function storeAwsSettings(StoreAwsSettingsRequest $request): JsonResponse
    {
        try {
            $settings = [
                'aws_access_key' => $request->aws_access_key,
                'aws_secret_key' => $request->aws_secret_key,
                'aws_region' => $request->aws_region,
                'aws_bucket_name' => $request->aws_bucket_name,
                'aws_base_url' => $request->aws_base_url,
            ];

            $success = $this->repository->updateAwsSettings($settings);

            if ($success) {
                return response()->json([
                    'code' => 200,
                    'message' => __('admin.general_settings.aws_success'),
                    'data' => [],
                ]);
            }

            throw new GeneralSettingUpdateException(__('admin.general_settings.update_failed'));
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
            ], 500);
        }
    }

    public function storeInvoiceSettings(StoreInvoiceSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->saveInvoiceSettings($request->validated());

            return response()->json([
                'code' => 200,
                'message' => __('admin.general_settings.invoice_setting_success'),
                'data' => [],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.invoice_setting_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(CompanySettingRequest $request): JsonResponse
    {
        try {
            $this->repository->storeCompanySettings($request->validated());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.company_setting_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeNotificationSettings(StoreNotificationSettingsRequest $request): JsonResponse
    {
        try {
            $this->repository->saveNotificationSettings($request->validated());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.notification_update_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.notification_error_update'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeSeoSetupSettings(StoreSeoSetupRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $groupId = $request->group_id ?? null;
            $this->repository->storeSeoSettings($data, $groupId);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.seo_update_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.seo_update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeMaintenanceSettings(StoreMaintenanceSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->storeMaintenanceSettings($request->validatedData());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.maintanance_update_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.maintanance_update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeCookiesSettings(StoreCookiesSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->storeCookiesSettings($request->validatedData());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.cookies_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.sretrive_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cookiesSettingsList(CookiesSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $settings = $repository->getCookiesSettings(
                $request->group_id,
                $request->language_id
            );

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.cookies_retrive_success'),
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function listCompany(ListCompanyRequest $request): JsonResponse
    {
        try {
            $data = $this->repository->getCompanySettings($request->group_id);

            if (! $data) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => __('admin.common.no_data_found'),
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(SettingListRequest $request): JsonResponse
    {
        try {
            $settings = $this->repository->getSettingsByGroup($request->validated()['group_id']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.setting_retrive_success'),
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function security(): View
    {
        return view('generalsetting::security.index');
    }

    public function checkCurrentPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth('admin')->user();

        $response = [
            'status' => 'error',
            'code' => 401,
            'message' => __('admin.general_settings.user_not_authenticated'),
        ];

        if ($user && $user->password) {
            $password = (string) $request->input('password');
            $result = \Hash::check($password, $user->password);

            $response = [
                'status' => $result ? 'success' : 'error',
                'code' => $result ? 200 : 422,
                'message' => __(
                    $result
                        ? 'admin.general_settings.current_password_correct'
                        : 'admin.general_settings.current_password_incorrect'
                ),
            ];
        }

        return response()->json($response);
    }

    public function checkCurrentPhoneNumber(Request $request): JsonResponse
    {
        $request->validate([
            'currentPhoneNumber' => 'required|string',
        ]);

        $user = auth('admin')->user();
        $status = 'error';
        $code = 422;
        $error = null;
        $message = '';

        if (! $user) {
            $code = 401;
            $error = 'unauthenticated';
            $message = __('admin.general_settings.user_not_authenticated');
        } elseif (! $user->phone_number) {
            $error = 'null';
            $message = __('admin.general_settings.phone_number_not_set');
        } elseif ($user->phone_number !== (string) $request->input('currentPhoneNumber')) {
            $error = 'incorrect';
            $message = __('admin.general_settings.phone_number_incorrect');
        } else {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.phone_number_correct'),
            ]);
        }

        return response()->json([
            'status' => $status,
            'code' => $code,
            'error' => $error,
            'message' => $message,
        ], $code);
    }


    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $result = $this->repository->updatePassword($request->validatedData());

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'code' => $result['success'] ? 200 : 422,
            'message' => $result['message'],
        ], $result['success'] ? 200 : 422);
    }

    public function updatePhoneNumber(UpdatePhoneNumberRequest $request): JsonResponse
    {
        $result = $this->repository->updatePhoneNumber($request->validatedData());

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'code' => $result['success'] ? 200 : 422,
            'message' => $result['message'],
        ], $result['success'] ? 200 : 422);
    }

    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        $result = $this->repository->updateEmail($request->validatedData());

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'code' => $result['success'] ? 200 : 422,
            'message' => $result['message'],
        ], $result['success'] ? 200 : 422);
    }

    public function getSecuritySettings(): JsonResponse
    {
        $data = $this->repository->getSecuritySettings();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function logoutDevice(LogoutDeviceRequest $request): JsonResponse
    {
        $result = $this->repository->logoutDevice($request->validatedData());

        return response()->json([
            'status' => $result['success'] ? 'success' : 'error',
            'code' => 200,
            'message' => $result['message'],
        ]);
    }

    public function updateGoogleAuth(Request $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::guard('admin')->user();
            $user->google_auth_enabled = $request->googleAuthEnabled === 'true' ? 1 : 0;
            $user->save();
            $message = $user->google_auth_enabled === 1 ? 'Google Authentication Enabled Successfully' : 'Google Authentication Disabled Successfully';
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => $message,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function updatePrefixes(Request $request): JsonResponse
    {
        try {
            $this->repository->updatePrefixes($request->all(), $request->group_id);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.prefix_settings_update_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.common.default_update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function paymentIndex(): View
    {
        return view('generalsetting::payment.index');
    }

    public function updatepaymentSettings(UpdatePaymentSettingsRequest $request): JsonResponse
    {
        try {
            $success = $this->repository->updatePaymentSettings($request->validatedData());

            if ($success) {
                return response()->json([
                    'code' => 200,
                    'message' => __('admin.general_settings.payment_updated_successfull'),
                ]);
            }

            throw new GeneralSettingUpdateException(__('admin.general_settings.update_failed'));
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.global_settings_error') . $e->getMessage(),
            ], 500);
        }
    }

    public function updatepaymentStatus(UpdatePaymentStatusRequest $request): JsonResponse
    {
        try {
            $success = $this->repository->updatePaymentStatus($request->validatedData());

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.general_settings.payment_updated_successfull'),
                ]);
            }

            throw new CustomException(__('admin.general_settings.update_failed'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function paymentList(Request $request): JsonResponse
    {
        $orderBy = $request->order_by ?? 'desc';
        $groupId = 13;

        try {
            $data = $this->repository->getPaymentSettings($groupId, $orderBy);

            return response()->json([
                'code' => 200,
                'message' => __('admin.general_settings.general_settings_success'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.global_settings_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function themeSettings(): View
    {
        return view('generalsetting::website_settings.theme_settings');
    }

    public function updateThemeSettings(UpdateThemeSettingsRequest $request, GeneralSettingInterface $repository): JsonResponse
    {
        try {
            $repository->updateThemeSettings($request->validated());

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.general_settings.theme_update_success'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.common.default_update_error'),
            ], 500);
        }
    }
}
