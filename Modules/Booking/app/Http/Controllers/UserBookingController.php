<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Booking\Repositories\Eloquent\UserBookingRepository;

class UserBookingController extends Controller
{
    protected $userBookingRepository;
    public function __construct()
    {
        $this->userBookingRepository = new UserBookingRepository();
    }

    public function createBooking(Request $request): JsonResponse
    {
        $data = $this->userBookingRepository->createBooking($request);
        return response()->json($data);
    }

    public function index(Request $request, $slug): View
    {
        $data = $this->userBookingRepository->index($slug);
        return view('booking::user_booking.index', $data)->with($request->all());
    }

    public function getStates($country_id): JsonResponse
    {
        $states = $this->userBookingRepository->getStates($country_id);
        return response()->json($states);
    }

    public function getCities($state_id): JsonResponse
    {
        $cities = $this->userBookingRepository->getCities($state_id);
        return response()->json($cities);
    }

    public function paymentSuccess($transaction_id): View
    {
        $data = $this->userBookingRepository->paymentSuccess($transaction_id);
        return view('booking::user_booking.success_page', $data);
    }

    public function userPayments(Request $request): JsonResponse
    {
        $response = $this->userBookingRepository->userPayments($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function paypalPaymentSuccess(Request $request): JsonResponse|RedirectResponse
    {
        $response = $this->userBookingRepository->paypalPaymentSuccess($request);
        if ($response['redirect']) {
            return redirect()->to($response['url']);
        }
        return response()->json($response, $response['code'] ?? 200);
    }

    public function stripPaymentSuccess(Request $request): JsonResponse|RedirectResponse
    {
        $response = $this->userBookingRepository->stripPaymentSuccess($request);
        if ($response['redirect']) {
            return redirect()->to($response['url']);
        }
        return response()->json($response, $response['code'] ?? 200);
    }
}
