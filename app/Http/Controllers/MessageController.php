<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Eloquent\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    protected MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    public function sellerMessages(): View
    {
        $data = $this->messageRepository->sellerMessages();
        return view('frontend.seller.messages.messages', $data);
    }

    public function sellerFetchMessages(Request $request): JsonResponse
    {
        $response = $this->messageRepository->sellerFetchMessages($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function sellerSendMessage(Request $request): JsonResponse
    {
        $response = $this->messageRepository->sellerSendMessage($request);
        return response()->json($response);
    }

    public function buyerMessages(): View
    {
        $data = $this->messageRepository->buyerMessages();
        return view('frontend.buyer.messages.messages', $data);
    }

    public function buyerFetchMessages(Request $request): JsonResponse
    {
        $response = $this->messageRepository->buyerFetchMessages($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function buyerSendMessage(Request $request): JsonResponse
    {
        $response = $this->messageRepository->buyerSendMessage($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $response = $this->messageRepository->searchUsers($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $response = $this->messageRepository->sendMessage($request);
        return response()->json($response);
    }

    public function adminMessages(): View
    {
        $users = User::where('user_type', 3)->orderBy('name', 'asc')->get();
        return view('admin.chat.messages', compact('users'));
    }

    public function fetchMessages(Request $request): JsonResponse
    {
        $response = $this->messageRepository->fetchMessages($request);
        return response()->json($response, $response['code'] ?? 200);
    }
}
