<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Modules\GeneralSetting\Models\SignatureSetting;

interface SignatureSettingInterface
{
    /**
     * @return Collection<int, SignatureSetting>
     */
    public function getAllSignatures(?string $search): Collection;

    /**
     * @param array{
     *     signature_name: string,
     *     is_default?: int,
     *     status?: int
     * } $data
     */
    public function createSignature(array $data, ?UploadedFile $image): SignatureSetting;

    /**
     * @param array{
     *     signature_name: string,
     *     is_default?: int,
     *     status?: int
     * } $data
     */
    public function updateSignature(int $id, array $data, ?UploadedFile $image): SignatureSetting;

    public function deleteSignature(int $id): int;

    public function getTotalSignaturesCount(): int;
}
