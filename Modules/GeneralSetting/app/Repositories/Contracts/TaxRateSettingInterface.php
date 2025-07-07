<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\TaxGroup;
use Modules\GeneralSetting\Models\TaxRate;

interface TaxRateSettingInterface
{
    /**
     * @param array{
     *     id?: int,
     *     tax_name: string,
     *     tax_rate: float,
     *     status?: bool
     * } $data
     */
    public function createOrUpdateTaxRate(array $data): bool;

    /**
     * @return Collection<int, TaxRate>
     */
    public function getTaxRateList(string $orderBy): Collection;

    public function deleteTaxRate(int $id): bool;

    public function findTaxRate(int $id): ?TaxRate;

    /**
     * @param array{
     *     id?: int,
     *     tax_group_name: string,
     *     status?: bool,
     *     sub_tax: array<int>
     * } $data
     */
    public function createOrUpdateTaxGroup(array $data): bool;

    /**
     * @return Collection<int, TaxGroup>
     */
    public function getTaxGroupList(string $orderBy): Collection;

    public function deleteTaxGroup(int $id): bool;

    public function findTaxGroup(int $id): ?TaxGroup;

    /**
     * @return Collection<int, TaxRate>
     */
    public function getActiveTaxRates(): Collection;
}
