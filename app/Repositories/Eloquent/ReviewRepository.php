<?php

namespace App\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function addReview(Request $request): JsonResponse
    {
        $userId = current_user()->id ?? $request->user_id;
        $response = [];
        $validator = Validator::make($request->all(), [
            'comments' => ['required', 'min:3'],
        ], [
            'comments.required' => __('web.home.comments_required'),
            'comments.min' => __('web.home.comments_minlength'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        if (! isBookingCompleted($request->gigs_id, $userId)) {
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => __('web.home.review_not_allowed'),
            ], 403);
        }

        try {
            Review::create([
                'gigs_id' => $request->gigs_id,
                'user_id' => $userId,
                'ratings' => $request->ratings ?? 0,
                'comments' => $request->comments,
            ]);

            $response = response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('web.home.review_create_success'),
            ]);
        } catch (\Exception $e) {
            $response = response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_create_error'),
                'error' => $e->getMessage(),
            ], 500);
        }

        return $response;
    }

    public function addReply(Request $request): JsonResponse
    {
        $userId = current_user()->id ?? $request->user_id;
        $response = [];
        $validator = Validator::make($request->all(), [
            'reply_comments' => ['required', 'min:3'],
        ], [
            'reply_comments.required' => __('web.home.reply_comments_required'),
            'reply_comments.min' => __('web.home.reply_comments_minlength'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $isBooking = isBookingCompleted($request->gigs_id, $userId);
        $gigOwnerId = gigOwnerUserId($request->gigs_id);

        if (! $isBooking && $userId !== $gigOwnerId) {
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => __('web.home.reply_not_allowed'),
            ], 403);
        }
        
        try {
            Review::create([
                'parent_id' => $request->review_id,
                'user_id' => $userId,
                'gigs_id' => $request->gigs_id,
                'comments' => $request->reply_comments,
            ]);

            $response = response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('web.home.reply_create_success'),
            ]);
        } catch (\Exception $e) {
            $response = response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('web.home.reply_create_error'),
                'error' => $e->getMessage(),
            ], 500);
        }

        return $response;
    }

    public function reviewsList(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'gigs_id' => 'required',
        ], [
            'gigs_id.required' => __('web.home.gigs_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $gigsId = $request->gigs_id;
            $perPage = 7;

            $reviewsQuery = Review::select('id', 'user_id', 'gigs_id', 'parent_id', 'ratings', 'comments', 'created_at')
                ->with([
                    'user:id,name',
                    'user.userDetail:id,user_id,first_name,last_name,profile_image',
                    'replies' => function ($query) {
                        $query->select('id', 'user_id', 'gigs_id', 'parent_id', 'comments', 'created_at')
                            ->with([
                                'user:id,name',
                                'user.userDetail:id,user_id,first_name,last_name,profile_image',
                            ]);
                    },
                ])
                ->where('parent_id', 0)
                ->where('gigs_id', $gigsId)
                ->orderBy('created_at', 'desc');

            $paginatedReviews = $reviewsQuery->paginate($perPage);

            $reviewsData = collect($paginatedReviews->items())->map(function ($review) {
                $review->review_date = Carbon::parse($review->created_at)->diffForHumans();

                /** @var \App\Models\User|null $user */
                $user = $review->user;

                if ($user && $user->userDetail) {
                    /** @var \App\Models\UserDetail $detail */
                    $detail = $user->userDetail;

                    $user->name = $detail->first_name
                        ? $detail->first_name . ' ' . $detail->last_name
                        : $user->name;

                    $user->profile_image = $detail->profile_image ?? uploadedAsset(null, 'profile');
                    unset($user->userDetail);
                }

                unset($review->created_at);

                foreach ($review->replies ?? [] as $reply) {
                    $reply->reply_date = Carbon::parse($reply->created_at)->diffForHumans();

                    if ($reply->user && $reply->user->userDetail) {
                        $rd = $reply->user->userDetail;
                        $reply->user->name = $rd->first_name
                            ? $rd->first_name . ' ' . $rd->last_name
                            : $reply->user->name;

                        $reply->user->profile_image = $rd->profile_image ?? uploadedAsset(null, 'profile');
                        unset($reply->user->userDetail);
                    }

                    unset($reply->created_at);
                }

                return $review;
            });

            $avgRatings = Review::where('gigs_id', $gigsId)->avg('ratings');
            $totalReviews = Review::where(['gigs_id' => $gigsId, 'parent_id' => 0])->count();

            $starRatings = Review::where('gigs_id', $gigsId)
                ->where('parent_id', 0)
                ->selectRaw('ratings, COUNT(*) as count')
                ->groupBy('ratings')
                ->pluck('count', 'ratings')
                ->toArray();

            $starRatingsCount = [];
            $starRatingsPercentage = [];
            foreach (range(1, 5) as $star) {
                $count = $starRatings[$star] ?? 0;
                $starRatingsCount["{$star}_star"] = $count;

                $percentage = $totalReviews > 0 ? number_format($count / $totalReviews * 100, 0) : 0;
                $starRatingsPercentage["{$star}_star"] = $percentage . '%';
            }
            $avgRatings = $avgRatings ? floatval($avgRatings) : 0;
            $finalData = [
                'reviews_meta' => [
                    'avg_ratings' => number_format($avgRatings, 1),
                    'total_reviews' => $totalReviews,
                    'star_ratings_count' => $starRatingsCount,
                    'star_ratings_percentage' => $starRatingsPercentage,
                ],
                'reviews' => $reviewsData,
                'current_page' => $paginatedReviews->currentPage(),
                'next_page' => $paginatedReviews->hasMorePages() ? $paginatedReviews->currentPage() + 1 : null,
            ];

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => $finalData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * @return array <string, mixed>
     */
    public function buyerReviewList(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $perPage = 5;

        $query = Review::with(['user.userDetail', 'gig'])
            ->where('user_id', $userId)
            ->where('parent_id', 0)
            ->orderBy('created_at', 'desc');

        $reviews = $query->paginate($perPage);

        return [
            'reviews' => $reviews,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function deleteReview(int $reviewId): array
    {
        $review = Review::find($reviewId);

        if (! $review) {
            return [
                'code' => 404,
                'message' => __('web.user.no_reviews_found'),
            ];
        }
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        if ($review->user_id !== $authUser->id) {
            return [
                'code' => 403,
                'message' => __('web.user.unauthorized_to_delete'),
            ];
        }

        $review->delete();

        return [
            'code' => 200,
            'message' => __('web.user.review_deleted_success'),
        ];
    }
    /**
     * @return array <string, mixed>
     */
    public function sellerReviewList(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $perPage = 5;

        $gigIds = Gigs::where('user_id', $userId)->pluck('id');

        $query = Review::with(['user.userDetail', 'gig'])
            ->whereIn('gigs_id', $gigIds)
            ->where('parent_id', 0)
            ->orderBy('created_at', 'desc');

        $reviews = $query->paginate($perPage);

        return [
            'reviews' => $reviews,
        ];
    }
    /**
     * @return array <string, mixed>
     */
    public function deleteSellerReview(int $reviewId): array
    {
        try {
            $response = [];
            $review = Review::findOrFail($reviewId);
            /** @var \App\Models\User $authUser */
            $authUser = current_user();
            $sellerId = $authUser->id;
            $sellerGigIds = Gigs::where('user_id', $sellerId)->pluck('id')->toArray();

            if (! in_array($review->gigs_id, $sellerGigIds)) {
                return [
                    'code' => 403,
                    'message' => __('web.user.unauthorized_to_delete'),
                ];
            }

            $review->delete();

            $response = [
                'code' => 200,
                'message' => __('web.user.review_deleted_success'),
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'code' => 404,
                'message' => __('web.user.no_reviews_found'),
            ];
        } catch (\Exception $e) {
            $response = [
                'code' => 500,
                'message' => __('web.user.error_deleting_review'),
            ];
        }

        return $response;
    }
}
