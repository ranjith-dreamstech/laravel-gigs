<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Communication\Http\Controllers\EmailController;
use Modules\GeneralSetting\Models\EmailTemplate;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\NotificationType;

class NotificationService
{
    /**
     * Send notification to user via email and in-app notification
     *
     * @param string $email
     * @param string $slug
     * @param array $notifyData
     * @return bool
     */
    public function sendNotification(string $email, string $slug, array $notifyData = []): bool
    {
        try {
            $notificationType = $this->getNotificationType($slug);
            if (!$notificationType) {
                Log::warning("Notification type not found for slug: {$slug}");
                return false;
            }

            $template = $this->getEmailTemplate($notificationType->id);
            if (!$template || !$email) {
                Log::warning("Email template not found or email is empty", [
                    'notification_type_id' => $notificationType->id,
                    'email' => $email
                ]);
                return false;
            }

            $parsedTemplate = $this->parseTemplate($template, $notificationType, $notifyData);
            
            if (!empty($parsedTemplate['subject'])) {
                $this->sendEmail($email, $parsedTemplate);
                $this->createInAppNotification($email, $parsedTemplate, $notifyData);
            }

            Log::info("Notification sent successfully", [
                'email' => $email,
                'slug' => $slug,
                'subject' => $parsedTemplate['subject']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send notification", [
                'email' => $email,
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send newsletter email
     *
     * @param string|array $emails
     * @param string $slug
     * @param array $notifyData
     * @return bool
     */
    public function sendNewsletterEmail(string|array $emails, string $slug, array $notifyData): bool
    {
        $emails = is_array($emails) ? $emails : [$emails];
        $successCount = 0;

        foreach ($emails as $email) {
            if ($this->sendNotification($email, $slug, $notifyData)) {
                $successCount++;
            }
        }

        return $successCount > 0;
    }

    /**
     * Check if gig notifications are enabled
     *
     * @return bool
     */
    public function isGigNotificationEnabled(): bool
    {
        $setting = GeneralSetting::where('group_id', 2)
            ->where('key', 'bookingUpdates')
            ->first();
            
        return $setting ? $setting->value === '1' : false;
    }

    /**
     * Get notification type by slug
     *
     * @param string $slug
     * @return NotificationType|null
     */
    private function getNotificationType(string $slug): ?NotificationType
    {
        return NotificationType::where('slug', $slug)->first();
    }

    /**
     * Get email template for notification type
     *
     * @param int $notificationTypeId
     * @return EmailTemplate|null
     */
    private function getEmailTemplate(int $notificationTypeId): ?EmailTemplate
    {
        return EmailTemplate::where('notification_type', $notificationTypeId)
            ->where('status', 1)
            ->first();
    }

    /**
     * Parse template with notification data
     *
     * @param EmailTemplate $template
     * @param NotificationType $notificationType
     * @param array $notifyData
     * @return array
     */
    private function parseTemplate(EmailTemplate $template, NotificationType $notificationType, array $notifyData): array
    {
        $placeholders = json_decode($notificationType->tags ?? '', true) ?? [];
        
        $replacePlaceholders = function ($text) use ($placeholders, $notifyData) {
            if (!$placeholders || !is_array($placeholders)) {
                return $text;
            }

            foreach ($placeholders as $tag) {
                $search = '{' . $tag . '}';
                $replace = $notifyData[$tag] ?? '';
                $text = str_replace($search, $replace, $text);
            }

            return $text;
        };

        return [
            'subject' => $replacePlaceholders($template->subject),
            'content' => $replacePlaceholders($template->description),
            'sms_content' => $replacePlaceholders($template->sms_content),
            'notification_content' => $replacePlaceholders($template->notification_content),
        ];
    }

    /**
     * Send email using EmailController
     *
     * @param string $email
     * @param array $parsedTemplate
     * @return void
     */
    private function sendEmail(string $email, array $parsedTemplate): void
    {
        $payload = [
            'to_email' => $email,
            'subject' => $parsedTemplate['subject'],
            'content' => $parsedTemplate['content'],
        ];
        
        $emailPayload = new Request($payload);
        $emailController = new EmailController();
        $emailController->sendEmail($emailPayload);
    }

    /**
     * Create in-app notification
     *
     * @param string $email
     * @param array $parsedTemplate
     * @param array $notifyData
     * @return void
     */
    private function createInAppNotification(string $email, array $parsedTemplate, array $notifyData): void
    {
        $user = User::where('email', $email)->first();
        
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'related_user_id' => $notifyData['related_user_id'] ?? null,
                'subject' => $parsedTemplate['subject'],
                'content' => $parsedTemplate['notification_content'],
            ]);
        }
    }

    /**
     * Get common notification data for templates
     *
     * @param array $notifyData
     * @return array
     */
    public function getCommonNotificationData(array $notifyData = []): array
    {
        $baseUrl = config('app.url');
        $appName = config('app.name');
        
        return array_merge([
            'app_name' => $appName,
            'app_url' => $baseUrl,
            'current_year' => date('Y'),
            'current_date' => now()->format('Y-m-d'),
            'current_time' => now()->format('H:i:s'),
        ], $notifyData);
    }

    /**
     * Mark notification as read
     *
     * @param int $notificationId
     * @param int $userId
     * @return bool
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();
            
        if ($notification) {
            $notification->read_at = now();
            return $notification->save();
        }
        
        return false;
    }

    /**
     * Get user notifications with pagination
     *
     * @param int $userId
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserNotifications(int $userId, int $perPage = 15)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get unread notification count for user
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}