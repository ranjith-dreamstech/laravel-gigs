<?php

namespace App\Repositories\Contracts;

interface HomeRepositoryInterface
{
    /** @return array <string, mixed> */
    public function fetchMaintenanceData(): array;
    /** @return array <string, mixed> */
    public function addToFavourite(int $id): array;
}
