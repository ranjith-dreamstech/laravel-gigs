<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Currency;

interface CurrencySettingInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function createOrUpdateCurrency(array $data): bool;

    /**
     * @param array<string, mixed> $filters
     *
     * @return Collection<int, Currency>|LengthAwarePaginator<int, Currency>
     */
    public function getCurrencyList(array $filters = [], int $perPage = 10): Collection|LengthAwarePaginator;

    public function findCurrency(int $id): ?Currency;

    public function deleteCurrency(int $id): bool;
}
