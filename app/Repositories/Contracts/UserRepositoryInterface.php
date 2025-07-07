<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    /** @return array <string, mixed> */
    public function fetchUserProfile(): array;
    /** @return array <string, mixed> */
    public function mySellerList(Request $request): array;
    /** @return array <string, mixed> */
    public function myBuyerList(Request $request): array;
    /** @return array <string, mixed> */
    public function buyerFavoriteList(Request $request): array;
    /** @return array <string, mixed> */
    public function getNotifications(Request $request): array;
    /** @return array <string, mixed> */
    public function markAllNotificationsAsRead(): array;
    /** @return array <string, mixed> */
    public function sellerNotifications(Request $request): array;
    /** @return array <string, mixed> */
    public function buyerNotifications(Request $request): array;
    /** @return array <string, mixed> */
    public function removeFavorite(int $id): array;
    /** @return array <string, mixed> */
    public function removeAllFavorites(): array;
}
