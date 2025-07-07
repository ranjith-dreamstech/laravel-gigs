<?php

namespace Modules\Installer\Repositories\Eloquent;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;
use Modules\Installer\Enums\InstallerInfo;
use Modules\Installer\Repositories\Contracts\PurchaseVerificationInterface;
use RuntimeException;

class PurchaseVerificationRepository implements PurchaseVerificationInterface
{
    public function verifyPurchaseCode(string $purchaseCode): array
    {
        $response = Http::asForm()->post(InstallerInfo::VERIFICATION_URL->value, [
            'purchase_code' => $purchaseCode,
        ]);

        $data = $response->json();

        // Validate response structure
        if (!is_array($data)) {
            throw new CustomException('Invalid verification response format');
        }

        return $data;
    }
}
