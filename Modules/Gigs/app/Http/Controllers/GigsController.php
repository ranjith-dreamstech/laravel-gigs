<?php

namespace Modules\Gigs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gigs;
use App\Models\GigsMeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Booking\Models\Booking;
use Modules\Category\Models\Categories;
use Modules\GeneralSetting\Models\Currency;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\Gigs\Repositories\Contracts\GigsInterface;

class GigsController extends Controller
{
    protected GigsInterface $gigsRepository;

    public function __construct(GigsInterface $gigsRepository)
    {
        $this->gigsRepository = $gigsRepository;
    }

    public function indexGigs(): View
    {
        return view('frontend.gigs.index');
    }

    public function indexGigsList(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->indexGigsList($request));
    }

    public function listIndex(): View
    {
        return view('admin.gigs.index');
    }

    public function list(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->list($request));
    }

    public function getAddons(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->getAddons($request->input('gig_id')));
    }

    public function getFaq(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->getFaq($request->input('gig_id')));
    }

    public function getImage(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->getImage($request->input('gig_id')));
    }

    public function craeteGigs(): View
    {
        $currencyId = GeneralSetting::where('key', 'currency_symbol')->first();
        $currency = Currency::where('id', $currencyId->value)->first();
        $currencySymbol = $currency->symbol ?? '$';
        $categories = Categories::where('status', 1)->whereNull('parent_id')->get();

        return view('frontend.gigs.store.index', compact('categories', 'currencySymbol'));
    }

    public function indexGigsEdit(string $slug): View
    {
        $gigs = Gigs::where('slug', $slug)->first();
        $categories = Categories::where('status', 1)
            ->whereNull('parent_id')
            ->get();

        return view('frontend.gigs.edit.index', compact('slug', 'gigs', 'categories'));
    }

    public function bookingdetails(): View
    {
        /** @var Gigs|null $gigsInfo */
        $gigsInfo = Gigs::find(11);

        if (! $gigsInfo) {
            abort(404, 'Gig not found');
        }

        $extraServices = DB::table('gigs_extra')
            ->where('gigs_id', $gigsInfo->id)
            ->get();

        $salesCount = Booking::where('gigs_id', $gigsInfo->id)->count();
        $formattedSalesCount = str_pad((string) $salesCount, 2, '0', STR_PAD_LEFT);
        $slug = $gigsInfo->slug;

        // Explicitly type the view name as view-string
        /** @var view-string $viewName */
        $viewName = 'frontend.gigs.gigsDetailsCopy';

        return view($viewName, [
            'gigsInfo' => $gigsInfo,
            'formattedSalesCount' => $formattedSalesCount,
            'extraServices' => $extraServices,
            'slug' => $slug,
        ]);
    }

    public function storeGigs(Request $request): JsonResponse
    {
        //enable error reporting
        ini_set('display_errors', 1);
        
        return response()->json($this->gigsRepository->storeGigs($request));
    }

    public function editGigs(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->editGigs($request));
    }

    public function getSub(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->getSubCategories($request->category_id));
    }

    public function recentlistApi(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->recentListApi($request));
    }

    public function listApi(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->listApi($request));
    }

    public function listDetailsApi(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->listDetailsApi($request->slug));
    }

    public function orderStatus(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->updateStatus($request->id, $request->status));
    }

    public function gigsDetails(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->gigDetails($request->id));
    }

    public function gigDetails(string $slug): View
    {
        $data = $this->gigsRepository->gigDetailsBySlug($slug);
        return view('admin.gigs.detail', $data);
    }

    public function deleteGigs(Request $request): JsonResponse
    {
        return response()->json($this->gigsRepository->deleteGigs($request->gig_id));
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'gig_id' => 'required|integer',
            'path' => 'required|string',
        ]);

        $meta = GigsMeta::where('gig_id', $request->gig_id)
            ->where('key', 'gigs_image')
            ->first();

        if ($meta && $meta->value) {
            $images = json_decode($meta->value, true);

            if (is_array($images)) {
                $filtered = array_values(array_filter($images, function ($imgPath) use ($request) {
                    return ltrim($imgPath, '/') !== ltrim($request->path, '/');
                }));

                $meta->value = json_encode($filtered);
                $meta->save();

                return response()->json(['status' => 'success', 'message' => 'Image deleted.']);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Image not found.'], 404);
    }
}
