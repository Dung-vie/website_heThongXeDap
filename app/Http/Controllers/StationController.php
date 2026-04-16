<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('station');
    }

    public function byWard(Request $request)
    {
        $wardCode = $request->ward_code;

        $stations = Station::where('ward_code', $wardCode)
            ->withCount([
                // Đếm xe đang có trong trạm (không tính đang thuê)
                'bikes as current_bikes' => fn($q) => $q->whereNotNull('station_id'),
            ])
            ->get()
            ->map(function ($s) {
                $emptySlots = $s->total_slots - $s->current_bikes;
                return [
                    'id'           => $s->id,
                    'name'         => $s->name,
                    'status'       => $s->status,
                    'current_bikes'=> $s->current_bikes,
                    'empty_slots'  => $emptySlots,
                    'total_slots'  => $s->total_slots,
                ];
            });

        return response()->json($stations);
    }

    /**
     * AJAX: Chi tiết trạm (accordion) + phân trang bình luận.
     */
    public function detail(Request $request, $id)
    {
        $station = Station::findOrFail($id);
        $page    = (int) $request->get('page', 1);

        // 5 đánh giá mỗi lần, có nút tải thêm
        $reviews = Review::with('user')
            ->where('station_id', $id)
            ->latest()
            ->paginate(5, ['*'], 'page', $page);

        $parked     = $station->bikes()->whereNotNull('station_id')->count();
        $emptySlots = $station->total_slots - $parked;

        return response()->json([
            'id'           => $station->id,
            'name'         => $station->name,
            'address'      => $station->address,
            'total_slots'  => $station->total_slots,
            'empty_slots'  => $emptySlots,
            'current_bikes'=> $parked,
            'status'       => $station->status,
            'reviews'      => $reviews->map(fn($r) => [
                'user_name'      => $r->user->name ?? 'Ẩn danh',
                'station_rating' => $r->station_rating,
                'station_comment'=> $r->station_comment,
            ]),
            'has_more'  => $reviews->hasMorePages(),
            'next_page' => $page + 1,
        ]);
    }
}
