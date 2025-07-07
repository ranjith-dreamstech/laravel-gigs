<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Repositories\Contracts\CurrencySettingInterface;

class CurrencySettingRepository implements CurrencySettingInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function createOrUpdateCurrency(array $data): bool
    {
        $currencyData = [
            'currency_name' => $data['currency_name'],
            'code' => $data['code'],
            'symbol' => $data['symbol'],
            'exchange_rate' => $data['exchange_rate'] ?? 0,
        ];

        if (! isset($data['id'])) {
            $currencyData['status'] = 1;
            return (bool) Currency::create($currencyData);
        }

        $currencyData['status'] = $data['status'] ?? 0;
        return Currency::where('id', $data['id'])->update($currencyData) > 0;
    }

    /**
     * @param array{
     *     keyword?: string,
     *     order_by?: string,
     *     paginate?: bool
     * } $filters
     *
     * @return Collection<int, Currency>|LengthAwarePaginator<int, Currency>
     */
    public function getCurrencyList(array $filters = [], int $perPage = 10): Collection|LengthAwarePaginator
    {
        $query = Currency::query();

        if (! empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('currency_name', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $filters['keyword'] . '%');
            });
        }

        if (isset($filters['order_by'])) {
            $query->orderBy('currency_name', $filters['order_by']);
        }

        if (isset($filters['paginate']) && $filters['paginate'] === false) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    public function findCurrency(int $id): ?Currency
    {
        return Currency::find($id);
    }

    public function deleteCurrency(int $id): bool
    {
        return Currency::where('id', $id)->delete() > 0;
    }
}
