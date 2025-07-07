<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\SubTax;
use Modules\GeneralSetting\Models\TaxGroup;
use Modules\GeneralSetting\Models\TaxRate;
use Modules\GeneralSetting\Repositories\Contracts\TaxRateSettingInterface;

class TaxRateSettingRepository implements TaxRateSettingInterface
{
    /**
     * @param array{
     *     id?: int,
     *     tax_name: string,
     *     tax_rate: float,
     *     status?: bool
     * } $data
     */
    public function createOrUpdateTaxRate(array $data): bool
    {
        if (! isset($data['id'])) {
            return (bool) TaxRate::create($data);
        }

        return (bool) TaxRate::where('id', $data['id'])->update($data);
    }

    /**
     * @return Collection<int, TaxRate>
     */
    public function getTaxRateList(string $orderBy): Collection
    {
        return TaxRate::orderBy('id', $orderBy)->get();
    }

    public function deleteTaxRate(int $id): bool
    {
        return (bool) TaxRate::where('id', $id)->delete();
    }

    public function findTaxRate(int $id): ?TaxRate
    {
        return TaxRate::find($id);
    }

    /**
     * @param array{
     *     id?: int,
     *     tax_group_name: string,
     *     status?: bool,
     *     sub_tax: array<int>
     * } $data
     */
    public function createOrUpdateTaxGroup(array $data): bool
    {
        if (! isset($data['id'])) {
            $group = TaxGroup::create(['tax_name' => $data['tax_group_name']]);
            foreach ($data['sub_tax'] as $taxRateId) {
                SubTax::updateOrCreate([
                    'tax_group_id' => $group->id,
                    'tax_rate_id' => $taxRateId,
                ]);
            }
            return true;
        }

        TaxGroup::where('id', $data['id'])->update([
            'tax_name' => $data['tax_group_name'],
            'status' => $data['status'] ?? 1,
        ]);

        SubTax::where('tax_group_id', $data['id'])
            ->whereNotIn('tax_rate_id', $data['sub_tax'])
            ->delete();

        foreach ($data['sub_tax'] as $taxRateId) {
            SubTax::updateOrCreate([
                'tax_group_id' => $data['id'],
                'tax_rate_id' => $taxRateId,
            ]);
        }

        return true;
    }

    /**
     * @return Collection<int, TaxGroup>
     */
    public function getTaxGroupList(string $orderBy): Collection
    {
        return TaxGroup::with(['taxRates:id,tax_name,tax_rate'])
            ->orderBy('id', $orderBy)
            ->get();
    }

    public function deleteTaxGroup(int $id): bool
    {
        return (bool) TaxGroup::where('id', $id)->delete();
    }

    public function findTaxGroup(int $id): ?TaxGroup
    {
        return TaxGroup::with('taxRates')->find($id);
    }

    /**
     * @return Collection<int, TaxRate>
     */
    public function getActiveTaxRates(): Collection
    {
        return TaxRate::where('status', 1)->get(['id', 'tax_name', 'tax_rate']);
    }
}
