<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

/**
 * Customer có thể:
 * - Thêm, xóa, sửa rating của mình
 * - Lọc các rating 
 *   (Khi vào trang sp đã rating, render theo mới nhất, có thể lọc lấy rating của chính user)
 */
class RatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with('user');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('only_me') && $request->only_me == '1') {
            $query->where('user_id', auth()->id());
        }

        return $query->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stars' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
        ]);

        // Nếu đã từng đánh giá => cập nhật
        $rating = Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'stars' => $request->stars,
                'description' => $request->description,
                'image_path' => $request->image_path,
            ]
        );

        return response()->json([
            'message' => 'Rating saved successfully',
            'data' => $rating,
        ]);
    }

    public function destroy($id)
    {
        $rating = Rating::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $rating->delete();

        return response()->json(['message' => 'Rating deleted']);
    }
}
