<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use App\Exceptions\CustomException;
use App\Services\ImageResizer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\GeneralSetting\Models\SignatureSetting;
use Modules\GeneralSetting\Repositories\Contracts\SignatureSettingInterface;

class SignatureSettingRepository implements SignatureSettingInterface
{
    protected ImageResizer $imageResizer;

    public function __construct(ImageResizer $imageResizer)
    {
        $this->imageResizer = $imageResizer;
    }

    /**
     * @return Collection<int, SignatureSetting>
     */
    public function getAllSignatures(?string $search): Collection
    {
        return SignatureSetting::when($search, function ($query) use ($search) {
            $query->where('signature_name', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (SignatureSetting $signature) {
                $signature->signature_image = uploadedAsset($signature->signature_image ?? '', 'default');
                return $signature;
            });
    }

    /**
     * @param array{
     *     signature_name: string,
     *     is_default?: int,
     *     status?: int
     * } $data
     */
    public function createSignature(array $data, ?UploadedFile $image): SignatureSetting
    {
        $imagePath = $image ? $this->uploadSignatureImage($image) : null;

        if (isset($data['is_default']) && $data['is_default'] === 1) {
            $this->resetDefaultSignature();
        }

        $isDefault = isset($data['is_default']) && $data['is_default'] === 1 ? 1 : 0;

        return SignatureSetting::create([
            'signature_name' => $data['signature_name'],
            'signature_image' => $imagePath,
            'status' => 1,
            'is_default' => $isDefault,
        ]);
    }

    /**
     * @param array{
     *     signature_name: string,
     *     is_default?: int,
     *     status?: int
     * } $data
     */
    public function updateSignature(int $id, array $data, ?UploadedFile $image): SignatureSetting
    {
        $signature = SignatureSetting::findOrFail($id);

        if ($image) {
            $this->deleteSignatureImage($signature->signature_image);
            $signature->signature_image = $this->uploadSignatureImage($image);
        }

        if (isset($data['is_default']) && $data['is_default'] === 1) {
            $this->resetDefaultSignature($id);
        }

        $isDefault = isset($data['is_default']) && $data['is_default'] === 1 ? 1 : 0;

        $signature->update([
            'signature_name' => $data['signature_name'],
            'is_default' => $isDefault,
            'status' => ! empty($data['status']) ? 1 : 0,
        ]);

        return $signature;
    }

    public function deleteSignature(int $id): int
    {
        $signature = SignatureSetting::findOrFail($id);
        $this->deleteSignatureImage($signature->signature_image);
        $signature->delete();
        return SignatureSetting::count();
    }

    public function getTotalSignaturesCount(): int
    {
        return SignatureSetting::count();
    }

    protected function resetDefaultSignature(?int $excludeId = null): void
    {
        $query = SignatureSetting::where('is_default', 1);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->update(['is_default' => 0]);
    }

    protected function uploadSignatureImage(UploadedFile $file): string
    {
        $path = $this->imageResizer->uploadFile($file, 'signatures', null);
        if (! $path) {
            throw new CustomException('Failed to upload signature image');
        }
        return $path;
    }

    protected function deleteSignatureImage(?string $imagePath): void
    {
        if ($imagePath) {
            Storage::delete($imagePath);
        }
    }
}
