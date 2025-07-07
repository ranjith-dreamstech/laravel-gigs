<?php

namespace App\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\Wishlist;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\Category\Models\Categories;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\TranslationLanguage;

class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * @return Collection<int, Categories>
     */
    public function fetchAllCategories(): Collection
    {
        $languageCode = App::getLocale();
        $transLanguage = TranslationLanguage::where('code', $languageCode)->first();

        if (! $transLanguage) {
            return collect(); // or throw exception if it's mandatory
        }

        return Categories::where('language_id', $transLanguage->id)
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * @return Collection<int, Gigs>
     */
    public function searchGigs(string $keyword): Collection
    {
        if (! empty($keyword)) {
            return Gigs::where('title', 'LIKE', "%{$keyword}%")
                ->select('id', 'title')
                ->get();
        }

        return collect(); // returns empty Collection<int, Gigs> implicitly
    }

    /**
     * @return array{
     *     slug: string,
     *     service: \App\Models\Gigs,
     *     gigsInfo: \App\Models\Gigs,
     *     formattedSalesCount: string,
     *     extraServices: \Illuminate\Support\Collection<int, \stdClass>,
     *     currencySymbol: string,
     *     isWishlisted: bool
     * }
     */
    public function serviceDetail(string $slug): array
    {
        $service = Gigs::where('slug', $slug)->first();
        if (! $service) {
            abort(404);
        }

        $gigsInfo = Gigs::find($service->id);
        if (! $gigsInfo) {
            abort(404);
        }

        $extraServices = DB::table('gigs_extra')
            ->where('gigs_id', $gigsInfo->id)
            ->get();

        $salesCount = Booking::where('gigs_id', $gigsInfo->id)->count();
        $formattedSalesCount = str_pad((string) $salesCount, 2, '0', STR_PAD_LEFT);

        $currencySetting = GeneralSetting::where('key', 'currency_symbol')->first();
        $currencySymbol = '$';
        if ($currencySetting) {
            $currency = Currency::find($currencySetting->value);
            $currencySymbol = $currency->symbol ?? '$';
        }

        $isWishlisted = false;
        $user = Auth::guard('web')->user();
        if ($user && Wishlist::where('user_id', $user->id)->where('service_id', $gigsInfo->id)->exists()) {
            $isWishlisted = true;
        }

        return [
            'slug' => $gigsInfo->slug,
            'service' => $service,
            'gigsInfo' => $gigsInfo,
            'formattedSalesCount' => $formattedSalesCount,
            'extraServices' => $extraServices,
            'currencySymbol' => $currencySymbol,
            'isWishlisted' => $isWishlisted,
        ];
    }
}
