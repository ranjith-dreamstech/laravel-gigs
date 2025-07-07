<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ReviewRepositoryInterface
{
    public function addReview(Request $request): JsonResponse;
    public function addReply(Request $request): JsonResponse;
    public function reviewsList(Request $request): JsonResponse;
    /** @return array <string, mixed> */
    public function buyerreviewlist(Request $request): array;
    /** @return array <string, mixed> */
    public function deleteReview(int $reviewId): array;
    /** @return array <string, mixed> */
    public function sellerReviewList(Request $request): array;
    /** @return array <string, mixed> */
    public function deleteSellerReview(int $reviewId): array;
}
