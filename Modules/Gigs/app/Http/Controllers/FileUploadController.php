<?php

namespace Modules\Gigs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Gigs\Repositories\Eloquent\FileUploadRepository;

class FileUploadController extends Controller
{
    protected FileUploadRepository $fileUploadRepository;
    public function __construct()
    {
        $this->fileUploadRepository = new fileUploadRepository();
    }

    public function index(): View
    {
        $data = $this->fileUploadRepository->index();
        return view('frontend.seller.file_upload.index', $data);
    }

    public function uploadedList(Request $request): JsonResponse
    {
        $data = $this->fileUploadRepository->uploadedList($request);
        return response()->json($data);
    }

    public function gigsList(Request $request): JsonResponse
    {
        $data = $this->fileUploadRepository->gigsList($request);
        return response()->json($data);
    }

    public function orderType(Request $request): JsonResponse
    {
        $data = $this->fileUploadRepository->orderType($request);
        return response()->json($data);
    }

    public function orderDelete(Request $request): JsonResponse
    {
        $data = $this->fileUploadRepository->orderDelete($request);
        return response()->json($data);
    }
}
