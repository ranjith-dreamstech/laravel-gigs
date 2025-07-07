<?php

namespace App\Http\Resources;

use App\Models\Gigs;
use App\Models\GigsMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'user_id' => $resource->user_id,
            'service_id' => $resource->service_id,
            'service' => $resource->gigs ? $resource->gigs->toArray() : [],
            'service_images' => $this->gigImages($resource->gigs),
        ];
    }

    /** @return array <string, mixed> */
    public function gigImages(?Gigs $service): array
    {
        if ($service === null) {
            return [];
        }
        $gigsMeta = GigsMeta::where('gig_id', $service->id)
            ->where('key', 'gigs_image')
            ->first();

        $images = [];

        if (! empty($gigsMeta && $gigsMeta->value)) {
            $images = json_decode($gigsMeta->value, true);

            $images = array_map(function ($image) {
                return uploadedAsset($image);
            }, $images);
        }

        return $images;
    }
}
