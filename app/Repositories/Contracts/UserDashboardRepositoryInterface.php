<?php

namespace App\Repositories\Contracts;

interface UserDashboardRepositoryInterface
{
    /** @return array <string, mixed> */
    public function getBuyerDashboardData(int $userId): array;
    /** @return array <string, mixed> */
    public function getSellerDashboardData(int $userId): array;
    /** @return array <string, mixed> */
    public function getPaymentsSaleStatistics(int $userId, int $year): array;
    /** @return array <string, mixed> */
    public function getGigsSalesStatistics(int $userId, int $year): array;
}
