<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\ReviewRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    protected ReviewRepository $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = new ReviewRepository();
    }

    public function addReview(Request $request): JsonResponse
    {
        return $this->reviewRepository->addReview($request);
    }

    public function addReply(Request $request): JsonResponse
    {
        return $this->reviewRepository->addReply($request);
    }

    public function reviewsList(Request $request): JsonResponse
    {
        return $this->reviewRepository->reviewsList($request);
    }

    public function getRatingDescription(float $rating): string
    {
        $description = 'Poor';

        if ($rating >= 4.5) {
            $description = 'Excellent';
        } elseif ($rating >= 4.0) {
            $description = 'Very Good';
        } elseif ($rating >= 3.5) {
            $description = 'Good';
        } elseif ($rating >= 3.0) {
            $description = 'Average';
        } elseif ($rating >= 2.0) {
            $description = 'Below Average';
        }

        return $description;
    }


    public function buyerReviewList(Request $request): View|JsonResponse
    {
        $data = $this->reviewRepository->buyerReviewList($request);

        if ($request->ajax()) {
            return response()->json([
                'data' => $data['reviews']->items(),
                'next_page' => $data['reviews']->hasMorePages() ? $data['reviews']->currentPage() + 1 : null,
            ]);
        }

        return view('frontend.buyer.reviews', $data);
    }

    public function deleteReview(int $reviewId): JsonResponse
    {
        $response = $this->reviewRepository->deleteReview($reviewId);
        return response()->json($response);
    }

    public function sellerReviewList(Request $request): View|JsonResponse
    {
        $data = $this->reviewRepository->sellerReviewList($request);

        if ($request->ajax()) {
            return response()->json([
                'data' => $data['reviews']->items(),
                'next_page' => $data['reviews']->hasMorePages() ? $data['reviews']->currentPage() + 1 : null,
            ]);
        }

        return view('frontend.seller.reviews', [
            'reviews' => $data['reviews'],
        ]);
    }

    public function deleteSellerReview(int $reviewId): JsonResponse
    {
        $response = $this->ReviewRepository->deleteSellerReview($reviewId);
        return response()->json($response);
    }
}
