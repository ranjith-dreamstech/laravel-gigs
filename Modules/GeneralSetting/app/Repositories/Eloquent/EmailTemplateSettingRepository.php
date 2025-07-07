<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Modules\GeneralSetting\Models\EmailTemplate;
use Modules\GeneralSetting\Models\NotificationTag;
use Modules\GeneralSetting\Models\NotificationType;
use Modules\GeneralSetting\Repositories\Contracts\EmailTemplateRepositoryInterface;

class EmailTemplateSettingRepository implements EmailTemplateRepositoryInterface
{
    /**
     * @return Collection<int, NotificationTag>
     */
    public function getAllNotificationTags(): Collection
    {
        return NotificationTag::where('status', true)->get();
    }

    /**
     * @return Collection<int, NotificationType>
     */
    public function getAllNotificationTypes(): Collection
    {
        return NotificationType::where('status', true)->get();
    }

    /**
     * @param array{
     *     keyword?: string,
     *     start?: int,
     *     length?: int
     * } $params
     *
     * @return array{
     *     data: SupportCollection<int, array{
     *         id: int,
     *         title: string,
     *         notification_type: string,
     *         subject: string|null,
     *         sms_content: string,
     *         notification_content: string,
     *         description: string|null,
     *         status: bool,
     *         formated_date: string
     *     }>,
     *     totalRecords: int,
     *     filteredRecords: int
     * }
     */
    public function getEmailTemplates(array $params): array
    {
        $query = EmailTemplate::query();

        if (! empty($params['keyword'])) {
            $query->where('title', 'like', '%' . $params['keyword'] . '%');
        }

        $totalRecords = $filteredRecords = $query->count();

        $emailTemplates = $query->orderBy('id', 'desc')
            ->skip($params['start'] ?? 0)
            ->take($params['length'] ?? 10)
            ->get()
            ->map(function (EmailTemplate $emailTemplate) {
                return [
                    'id' => $emailTemplate->id,
                    'title' => $emailTemplate->title,
                    'notification_type' => $emailTemplate->notification_type,
                    'subject' => $emailTemplate->subject,
                    'sms_content' => $emailTemplate->sms_content,
                    'notification_content' => $emailTemplate->notification_content ?? '',
                    'description' => $emailTemplate->description,
                    'status' => (bool) $emailTemplate->status,
                    'formated_date' => formatDateTime($emailTemplate->created_at),
                ];
            });

        return [
            'data' => new SupportCollection($emailTemplates),
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords,
        ];
    }

    public function getEmailTemplateById(int $id): ?EmailTemplate
    {
        return EmailTemplate::find($id);
    }

    /**
     * @param array{
     *     id?: int,
     *     status?: string,
     *     title: string,
     *     notification_type: string,
     *     subject: string,
     *     sms_content: string,
     *     notification_content?: string,
     *     description: string
     * } $data
     */
    public function createOrUpdateEmailTemplate(array $data): EmailTemplate
    {
        $emailTemplate = ! empty($data['id'])
            ? EmailTemplate::findOrFail($data['id'])
            : new EmailTemplate();

        $emailTemplate->status = ! empty($data['status']) && $data['status'] === 'on' ? 1 : 0;
        $emailTemplate->title = $data['title'];
        $emailTemplate->notification_type = $data['notification_type'];
        $emailTemplate->subject = $data['subject'];
        $emailTemplate->sms_content = $data['sms_content'];
        $emailTemplate->notification_content = $data['notification_content'] ?? '';
        $emailTemplate->description = $data['description'];
        $emailTemplate->save();

        return $emailTemplate;
    }

    public function deleteEmailTemplate(int $id): void
    {
        EmailTemplate::findOrFail($id)->delete();
    }

    /**
     * @return array{
     *     notification_type: NotificationType|null,
     *     tags: array<string>
     * }
     */
    public function getTagsByNotificationType(int $notificationTypeId): array
    {
        $notificationType = NotificationType::find($notificationTypeId);
        $defaultTags = NotificationTag::where('status', true)->pluck('title')->toArray();

        return [
            'notification_type' => $notificationType,
            'tags' => $notificationType && $notificationType->tags
                ? json_decode($notificationType->tags, true) ?? []
                : $defaultTags,
        ];
    }
}
