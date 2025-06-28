<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

/**
 * Public frontend banner API
 */
class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::where('is_active', true);

        if ($request->has('section')) {
            $query->where('section_name', $request->section);
        }

        return $query->orderBy('position')->get();
    }
}
