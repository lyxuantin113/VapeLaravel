<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->has('section')) {
            $query->where('section_name', $request->section);
        }

        return $query->orderBy('position')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section_name' => 'required|string',
            'image_path' => 'required|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'position' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        return Banner::create($data);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'section_name' => 'sometimes|string',
            'image_path' => 'sometimes|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'position' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $banner->update($data);
        return $banner;
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
