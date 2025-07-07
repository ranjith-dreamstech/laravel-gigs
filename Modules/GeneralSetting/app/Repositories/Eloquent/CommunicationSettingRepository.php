<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\GeneralSetting\Models\CommunicationSetting;
use Modules\GeneralSetting\Repositories\Contracts\CommunicationSettingInterface;

class CommunicationSettingRepository implements CommunicationSettingInterface
{
    public function smsGateway(): View
    {
        return view('generalsetting::system_settings.sms-gateway');
    }

    public function emailSettings(): View
    {
        return view('generalsetting::system_settings.email_settings');
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array{message: string, data: CommunicationSetting}
     */
    public function statusUpdate(array $data): array
    {
        $type = $data['gateway'];
        $status = $data['status'];
        $settype = '';

        if (in_array($type, ['nexmo', 'twilio', 'twofactor'])) {
            $settype = 2;
        } elseif (in_array($type, ['phpmail', 'smtp', 'sendgrid'])) {
            $settype = 1;
        }

        $saveSiteKey = CommunicationSetting::updateOrCreate(
            ['key' => $type . '_status'],
            ['value' => $status, 'settings_type' => $settype, 'type' => $type]
        );

        if ($status === 1) {
            CommunicationSetting::where('settings_type', $settype)
                ->where('type', '!=', $type)
                ->where('key', 'LIKE', '%_status')
                ->update(['value' => 0]);
        }

        return [
            'message' => $status === 1
                ? __('admin.general_settings.activated_successfully')
                : __('admin.general_settings.deactivated_successfully'),
            'data' => $saveSiteKey,
        ];
    }

    /**
     * @param array{type: int} $filters
     *
     * @return array{message: string, data: array{settings: Collection<int, CommunicationSetting>}}
     */
    public function smsList(array $filters): array
    {
        $settings = CommunicationSetting::select('key', 'value', 'type')
            ->where('settings_type', $filters['type'])
            ->get();

        return [
            'message' => __('admin.general_settings.data_retrived_successfully'),
            'data' => ['settings' => $settings],
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array{message: string, data: array<empty, empty>}
     */
    public function storeCommunicationSetting(array $data): array
    {
        $settingsType = $this->getSettingsType($data['type']);
        $settings = $this->getSettingsData($data);

        foreach ($settings as $key => $value) {
            $this->updateOrCreateSetting($key, $value, $settingsType, $data['type']);
        }

        return [
            'message' => __('admin.general_settings.settings_update_success'),
            'data' => [],
        ];
    }

    /**
     * @return array{message: string}
     */
    public function sendTestMail(Request $request): array
    {
        $user = Auth::guard('admin')->user();
        $userId = $user->id ?? null;

        $userDetail = UserDetail::where('user_id', $userId)->first();
        $name = $userDetail && $userDetail->first_name
            ? $userDetail->first_name . ' ' . $userDetail->last_name
            : 'Admin';

        $notifyData = [
            'user_name' => $name,
        ];

        sendNewsletterEmail($request->email_address, 'test_mail', $notifyData);

        return [
            'message' => __('admin.general_settings.test_mail_sent_success'),
        ];
    }

    /**
     * @return int
     */
    private function getSettingsType(string $type): int
    {
        return match ($type) {
            'nexmo', 'twofactor', 'twilio' => 2,
            'smtp', 'phpmail', 'sendgrid' => 1,
            'notification_settings', 'fcm' => 3,
            default => 0,
        };
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, string|int>
     */
    private function getSettingsData(array $data): array
    {
        return match ($data['type']) {
            'nexmo' => [
                'nexmo_api_key' => $data['nexmo_api_key'],
                'nexmo_secret_key' => $data['nexmo_secret_key'],
                'nexmo_sender_id' => $data['nexmo_sender_id'],
            ],
            'twofactor' => [
                'twofactor_api_key' => $data['twofactor_api_key'],
                'twofactor_secret_key' => $data['twofactor_secret_key'],
                'twofactor_sender_id' => $data['twofactor_sender_id'],
            ],
            'twilio' => [
                'twilio_api_key' => $data['twilio_api_key'],
                'twilio_secret_key' => $data['twilio_secret_key'],
                'twilio_sender_id' => $data['twilio_sender_id'],
            ],
            'smtp' => [
                'smtp_from_email' => $data['smtp_from_email'],
                'smtp_password' => $data['smtp_password'],
                'smtp_from_name' => $data['smtp_from_name'],
                'smtp_port' => $data['smtp_port'],
                'smtp_host' => $data['smtp_host'],
            ],
            'phpmail' => [
                'phpmail_from_email' => $data['phpmail_from_email'],
                'phpmail_password' => $data['phpmail_password'],
                'phpmail_from_name' => $data['phpmail_from_name'],
            ],
            'sendgrid' => [
                'sendgrid_from_email' => $data['sendgrid_from_email'],
                'sendgrid_key' => $data['sendgrid_key'],
            ],
            'fcm' => [
                'project_id' => $data['project_id'],
                'client_email' => $data['client_email'],
                'private_key' => $data['private_key'],
            ],
            'notification_settings' => [
                'emailNotifications' => isset($data['emailNotifications']) && $data['emailNotifications'] === 'on' ? 1 : 0,
                'pushNotifications' => isset($data['pushNotifications']) && $data['pushNotifications'] === 'on' ? 1 : 0,
                'smsNotifications' => isset($data['smsNotifications']) && $data['smsNotifications'] === 'on' ? 1 : 0,
            ],
            default => [],
        };
    }

    private function updateOrCreateSetting(string $key, string|int $value, int $settingsType, string $type): CommunicationSetting
    {
        return CommunicationSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'settings_type' => $settingsType, 'type' => $type]
        );
    }
}
