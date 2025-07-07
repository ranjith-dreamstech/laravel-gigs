<?php

namespace App\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\Wishlist;
use App\Repositories\Contracts\HomeRepositoryInterface;
use Modules\GeneralSetting\Models\GeneralSetting;

class HomeRepository implements HomeRepositoryInterface
{
    /**
     * @return array <string, mixed>
     */
    public function fetchMaintenanceData(): array
    {
        $title = config('app.name', 'Dreams Gigs') . ' - Maintenance';
        $maintenance = GeneralSetting::where('group_id', 4)->pluck('value', 'key')->toArray();
        return [
            'image' => $maintenance['maintenance_image'] ? uploadedAsset($maintenance['maintenance_image']) : '',
            'description' => $maintenance['maintenance_description'] ?? '',
            'title' => $title,
        ];
    }

    /** @return array <string, mixed> */
    public function addToFavourite(int $id): array
    {
        $gig = Gigs::find($id);
        if (! $gig) {
            return [
                'status' => false,
                'message' => __('web.home.gig_not_found'),
            ];
        }
        /** @var \App\Models\User $user */
        $user = current_user();

        $wishlist = Wishlist::where('user_id', $user->id)->where('service_id', $gig->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return [
                'status' => true,
                'message' => __('web.home.gig_removed_from_favourite'),
            ];
        }
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->service_id = $gig->id;
        $wishlist->save();

        return [
            'status' => true,
            'message' => __('web.home.gig_added_to_favourite'),
        ];
    }
}
