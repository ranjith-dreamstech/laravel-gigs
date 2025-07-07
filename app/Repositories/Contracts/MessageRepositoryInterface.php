<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
    /** @return array <string, mixed> */
    public function sellerMessages(): array;
    /** @return array <string, mixed> */
    public function sellerFetchMessages(Request $request): array;
    /** @return array <string, mixed> */
    public function sellerSendMessage(Request $request): array;
    /** @return array <string, mixed> */
    public function buyerMessages(): array;
    /** @return array <string, mixed> */
    public function buyerFetchMessages(Request $request): array;
    /** @return array <string, mixed> */
    public function buyerSendMessage(Request $request): array;
    /** @return array <string, mixed> */
    public function searchUsers(Request $request): array;
    /** @return array <string, mixed> */
    public function sendMessage(Request $request): array;
    /** @return array <string, mixed> */
    public function fetchMessages(Request $request): array;
}
