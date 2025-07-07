<?php

namespace App\Http\Resources;

use App\Models\Gigs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource
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
            'name' => $resource->name,
            'image' => uploadedAsset($resource->image),
            'icon' => uploadedAsset($resource->icon),
            'desc' => $resource->description,
            'slug' => $resource->slug,
            'avg_price' => $this->calculateAveragePrice($resource->id),
            'currency' => getDefaultCurrencySymbol(),
            'total_services' => $this->getTotalServices($resource->id),
        ];
    }

    /** @return float */
    public function calculateAveragePrice(int $category_id): float
    {
        $averagePrice = Gigs::where('category_id', $category_id)->avg('general_price') ?? 0;
        return round((float) $averagePrice, 2);
    }

    /** @return int */
    public function getTotalServices(int $category_id): int
    {
        $languageCode = App::getLocale();
        $languageId = getLanguageId($languageCode);
        return Gigs::where('category_id', $category_id)->where('language_id', $languageId)->where('status', 1)->count();
    }
}
