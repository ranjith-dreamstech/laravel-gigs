<?php

namespace Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Page\Models\Section;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', 'asc');
        $sortBy = $request->input('sort_by', 'id');

        $sections = Section::orderBy($sortBy, $orderBy)->where("theme_id", $request->theme_id)->where("status", 1)->get();

        $data = [];
        $baseUrl = asset('storage/uploads');

        foreach ($sections as $section) {
            $decodedDatas = json_decode($section->datas, true);

            if (isset($decodedDatas['background_image'])) {
                $decodedDatas['background_image'] = $baseUrl . '/background_image_banner/' . $decodedDatas['background_image'];
            }

            if (isset($decodedDatas['thumbnail_image'])) {
                $decodedDatas['thumbnail_image'] = $baseUrl . '/thumbnail_image_banner/' . $decodedDatas['thumbnail_image'];
            }

            $data[] = array_merge([
                'id' => $section->id,
                'name' => $section->name,
                'status' => $section->status,
            ], $decodedDatas);

            $theme = [
                'theme_id' => $section->theme_id,
            ];
        }


        return response()->json(['code' => 200, 'message' => __('Section details retrieved successfully.'), 'data' => $data, 'theme' => $theme], 200);
    }

    public function indexListSection(Request $request)
    {
        $orderBy = $request->input('order_by', 'asc');
        $sortBy = $request->input('sort_by', 'id');
        $authuser = current_user();
        $language_id = $authuser->language_id;

        $allowedNames = ['Banner One', 'How It Work'];

        $sections = Section::orderBy($sortBy, $orderBy)
            ->where('status', 1)
            ->whereIn('name', $allowedNames)
            ->get();

        $data = [];
        $baseUrl = asset('storage');

        foreach ($sections as $section) {
            // Fetch matching section_datas row
            $sectionData = DB::table('section_datas')
                ->where('section_id', $section->id)
                ->where('language_id', $language_id)
                ->value('datas');

            $decodedDatas = $sectionData ? json_decode($sectionData, true) : [];

            // Update image URLs
            if (!empty($decodedDatas['thumbnail_image_one'])) {
                $decodedDatas['thumbnail_image_one'] = $baseUrl . '/' . $decodedDatas['thumbnail_image_one'];
            }

            if (!empty($decodedDatas['thumbnail_image_two'])) {
                $decodedDatas['thumbnail_image_two'] = $baseUrl . '/' . $decodedDatas['thumbnail_image_two'];
            }

            $data[] = array_merge([
                'id' => $section->id,
                'theme_id' => $section->theme_id,
                'name' => $section->name,
                'status' => $section->status,
            ], $decodedDatas);
        }

        return response()->json([
            'code' => 200,
            'message' => __('Section details retrieved successfully.'),
            'data' => $data
        ], 200);
    }

    public function indexSection()
    {
        return view('page::section.index');
    }


    public function store(Request $request)
    {
        $authuser = current_user();
        $language_id = $authuser->language_id;

        $rules = [];

        if ($request->section_id == 1) {
            $rules['description_one'] = 'required';
            $rules['label_one'] = 'required';
            $rules['line_one'] = 'required';
            $rules['line_two'] = 'required';
        } elseif ($request->section_id == 29) {
            $rules['description_two'] = 'required';
            $rules['label_two'] = 'required';
        } elseif ($request->section_id == 42) {
            $rules['label_1'] = 'required|max:50';
            $rules['dis_1'] = 'required|max:100';
            $rules['label_2'] = 'required|max:50';
            $rules['dis_2'] = 'required|max:100';
            $rules['label_3'] = 'required|max:50';
            $rules['dis_3'] = 'required|max:100';
            $rules['label_4'] = 'required|max:50';
            $rules['dis_4'] = 'required|max:100';
            $rules['label_5'] = 'required|max:50';
            $rules['dis_5'] = 'required|max:100';
            $rules['label_6'] = 'required|max:50';
            $rules['dis_6'] = 'required|max:100';
        } elseif ($request->section_id == 15) {
            for ($i = 1; $i <= 5; $i++) {
                $rules["image_$i"] = 'nullable|mimes:jpeg,png,jpg,gif,webp,svg|max:8000';
            }
        } else {
            return response()->json(['message' => 'Invalid section ID'], 400);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = [];
        $id = $request->section_id;

        $existingData = DB::table('section_datas')
            ->where('section_id', $id)
            ->where('language_id', $language_id)
            ->value('datas');

        $existingData = $existingData ? json_decode($existingData, true) : [];

        if ($request->section_id == 1) {
            $thumbnailPath = $existingData['thumbnail_image_one'] ?? null;

            if ($request->hasFile('thumbnail_image_one')) {
                $thumbnailPath = uploadFile($request->file('thumbnail_image_one'), 'thumbnail_image_banner_one');
            }

            $data = [
                'label_one' => $request->label_one,
                'line_one' => $request->line_one,
                'line_two' => $request->line_two,
                'description_one' => $request->description_one,
                'thumbnail_image_one' => $thumbnailPath,
            ];
        } elseif ($request->section_id == 29) {
            $thumbnailPath = $existingData['thumbnail_image_two'] ?? null;

            if ($request->hasFile('thumbnail_image_two')) {
                $thumbnailPath = uploadFile($request->file('thumbnail_image_two'), 'thumbnail_image_banner_two');
            }

            $data = [
                'label_two' => $request->label_two,
                'description_two' => $request->description_two,
                'thumbnail_image_two' => $thumbnailPath,
            ];
        } elseif ($request->section_id == 42) {
            $data = [
                'label_1' => $request->label_1,
                'dis_1'   => $request->dis_1,
                'label_2' => $request->label_2,
                'dis_2'   => $request->dis_2,
                'label_3' => $request->label_3,
                'dis_3'   => $request->dis_3,
                'label_4' => $request->label_4,
                'dis_4'   => $request->dis_4,
                'label_5' => $request->label_5,
                'dis_5'   => $request->dis_5,
                'label_6' => $request->label_6,
                'dis_6'   => $request->dis_6,
            ];
        }

        try {
            $updated = DB::table('section_datas')
                ->where('section_id', $id)
                ->where('language_id', $language_id)
                ->update(['datas' => json_encode($data)]);

            if ($updated === 0) {
                DB::table('section_datas')->insert([
                    'section_id' => $id,
                    'language_id' => $language_id,
                    'datas' => json_encode($data),
                ]);
            }

            return response()->json(['code' => 200, 'message' => __('admin.cms.section_update_success')], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('admin.common.default_update_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
