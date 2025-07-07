<?php

namespace Modules\Communication\Helpers;

use App\Exceptions\SendGridConfigurationException;
use Illuminate\Support\Facades\Config;
use Modules\GeneralSetting\Models\CommunicationSetting;

class MailConfigurator
{
    /**
     * Configure mail settings based on communication settings.
     */
    public static function configureMail(): void
    {
        $settings = CommunicationSetting::where('settings_type', 1)
            ->whereIn('key', ['phpmail_status', 'smtp_status', 'sendgrid_status'])
            ->where('value', 1)
            ->first();

        if (! $settings) {
            return;
        }

        if ($settings->key === 'phpmail_status') {
            self::configurePhpMail();
        } elseif ($settings->key === 'smtp_status') {
            self::configureSmtpMail();
        } elseif ($settings->key === 'sendgrid_status') {
            self::configureSendGrid();
        }
    }

    private static function configurePhpMail()
    {
        $phpmail = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'phpmail')
            ->where('key', 'phpmail_from_email')
            ->value('value');

        $phpusername = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'phpmail')
            ->where('key', 'phpmail_from_name')
            ->value('value');

        if ($phpmail) {
            Config::set('mail.from.address', $phpmail);
            Config::set('mail.from.name', $phpusername ?? 'No-Reply');
            Config::set('mail.default', 'mail');
            Config::set('mail.mailers.mail', ['transport' => 'mail']);
        }
    }

    private static function configureSmtpMail()
    {
        $getmail = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'smtp')
            ->where('key', 'smtp_from_email')
            ->value('value');

        $getpassword = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'smtp')
            ->where('key', 'smtp_password')
            ->value('value');

        $getusername = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'smtp')
            ->where('key', 'smtp_from_name')
            ->value('value');

        $gethost = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'smtp')
            ->where('key', 'smtp_host')
            ->value('value');

        if (! $getmail || ! $getpassword || ! $gethost) {
            throw new SendGridConfigurationException('SMTP settings are incomplete.');
        }

        Config::set('mail.from.address', $getmail);
        Config::set('mail.from.name', $getusername ?? 'No-Reply');
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $gethost,
            'port' => 587,
            'encryption' => 'tls',
            'username' => $getmail,
            'password' => $getpassword,
        ]);
    }

    private static function configureSendGrid()
    {
        $getmail = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'sendgrid')
            ->where('key', 'sendgrid_from_email')
            ->value('value');

        $getkey = CommunicationSetting::where('settings_type', 1)
            ->where('type', 'sendgrid')
            ->where('key', 'sendgrid_key')
            ->value('value');

        if (! $getmail || ! $getkey) {
            throw new SendGridConfigurationException('SendGrid settings are incomplete.');
        }

        Config::set('mail.from.address', $getmail);
        Config::set('mail.from.name', 'Truelysell');
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => 'smtp.sendgrid.net',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'apikey',
            'password' => $getkey,
        ]);
    }
}
