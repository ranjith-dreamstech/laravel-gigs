<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\EmailTemplate;
use Modules\GeneralSetting\Models\NotificationTag;
use Modules\GeneralSetting\Models\NotificationType;

interface EmailTemplateRepositoryInterface
{
    /**
     * @return Collection<int, NotificationTag>
     */
    public function getAllNotificationTags(): Collection;

    /**
     * @return Collection<int, NotificationType>
     */
    public function getAllNotificationTypes(): Collection;

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEmailTemplates(array $params): array;

    public function getEmailTemplateById(int $id): ?EmailTemplate;

    /**
     * @param array<string, mixed> $data
     */
    public function createOrUpdateEmailTemplate(array $data): EmailTemplate;

    public function deleteEmailTemplate(int $id): void;

    /**
     * @return array<string, mixed>
     */
    public function getTagsByNotificationType(int $notificationTypeId): array;
}
