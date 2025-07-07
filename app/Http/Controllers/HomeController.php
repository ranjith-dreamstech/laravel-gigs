<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\HomeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\GeneralSetting\Models\GeneralSetting;

class HomeController extends Controller
{
    protected HomeRepository $homeRepository;

    public function __construct()
    {
        $this->homeRepository = new HomeRepository();
    }

    /**
     * Display the home page.
     *
     * @return View
     */
    public function index(): View
    {
        $defaultTheme = GeneralSetting::where('key', 'default_theme')->first();
        $theme = $defaultTheme->value ?? 1;
        $viewFileName = 'home_' . $theme;

        /** @var non-falsy-string $viewPath */
        $viewPath = 'frontend.home.' . $viewFileName;
        if (! view()->exists($viewPath)) {
            abort(404);
        }
        return view($viewPath);
    }

    /**
     * Show the maintenance page.
     *
     * @return View
     */
    public function maintenance(): View
    {
        $data = [
            'response' => $this->homeRepository->fetchMaintenanceData(),
        ];

        return view('frontend.home.maintenance', $data);
    }

    /**
     * Handle add/remove favourite.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addToFavourite(Request $request): JsonResponse
    {
        $response = $this->homeRepository->addToFavourite((int) $request->id);
        return response()->json($response);
    }
}
