<?php

namespace Modules\GeneralSetting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\GeneralSetting\Http\Requests\CommunicationSettingRequest;
use Modules\GeneralSetting\Repositories\Contracts\CommunicationSettingInterface;

class CommunicationSettingController extends Controller
{
    protected CommunicationSettingInterface $communicationSetting;

    public function __construct(CommunicationSettingInterface $communicationSetting)
    {
        $this->communicationSetting = $communicationSetting;
    }

    public function smsGateway(): View
    {
        return $this->communicationSetting->smsGateway();
    }

    public function emailSettings(): View
    {
        return $this->communicationSetting->emailSettings();
    }

    public function statusUpdate(CommunicationSettingRequest $request): JsonResponse
    {
        try {
            $result = $this->communicationSetting->statusUpdate($request->validated());
            return response()->json([
                'code' => 200,
                'message' => $result['message'],
                'data' => $result['data'],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function smsList(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|integer|min:1',
            ]);

            /** @var array{type: int} $filters */
            $filters = ['type' => (int) $validated['type']];

            $result = $this->communicationSetting->smsList($filters);

            return response()->json([
                'code' => 200,
                'message' => $result['message'],
                'data' => $result['data'],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.general_settings.data_failed_to_retrive'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeCommunicationSetting(CommunicationSettingRequest $request): JsonResponse
    {
        try {
            $result = $this->communicationSetting->storeCommunicationSetting($request->validated());
            return response()->json([
                'code' => 200,
                'message' => $result['message'],
                'data' => $result['data'],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.common.default_update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendTestMail(Request $request): JsonResponse
    {
        try {
            $result = $this->communicationSetting->sendTestMail($request);
            return response()->json([
                'code' => 200,
                'message' => $result['message'],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => __('admin.general_settings.test_mail_sent_fail'),
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
