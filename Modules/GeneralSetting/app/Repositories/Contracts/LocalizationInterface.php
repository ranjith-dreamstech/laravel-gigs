<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\Timezone;

interface LocalizationInterface
{
    /**
     * @return Collection<int, Timezone>
     */
    public function getTimezones(): Collection;

    /**
     * @return SupportCollection<int, array{id: int, text: string}>
     */
    public function searchTimezones(string $search): SupportCollection;

    public function getCurrentTimezone(): ?GeneralSetting;

    /**
     * @param array<string, mixed> $data
     */
    public function updateLocalization(array $data): void;

    public function getTimezoneById(int $id): ?Timezone;
}
