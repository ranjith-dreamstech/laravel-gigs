<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\GeneralSetting\Models\CommunicationSetting;

interface CommunicationSettingInterface
{
    public function smsGateway(): View;

    public function emailSettings(): View;

    /**
     * @param array<string, mixed> $data
     *
     * @return array{message: string, data: CommunicationSetting}
     */
    public function statusUpdate(array $data): array;

    /**
     * @param array{type: int} $filters
     *
     * @return array{message: string, data: array{settings: \Illuminate\Database\Eloquent\Collection<int, CommunicationSetting>}}
     */
    public function smsList(array $filters): array;

    /**
     * @param array<string, mixed> $data
     *
     * @return array{message: string, data: array<empty, empty>}
     */
    public function storeCommunicationSetting(array $data): array;

    /**
     * @return array{message: string}
     */
    public function sendTestMail(Request $request): array;
}
