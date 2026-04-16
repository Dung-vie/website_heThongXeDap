<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Rental;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function rentForm()
    {
        $user = Auth::user();
        $activeRental = $user->activeRental()->with(['bike', 'pickupStation'])->first();

        return view('rental.rent', compact('activeRental'));
    }

    public function rent(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra đang có xe chưa trả
        if ($user->activeRental) {
            return back()->withErrors(['msg' => 'Bạn đang có xe chưa trả, không thể thuê thêm.']);
        }

        $request->validate([
            'bike_id'           => 'required|exists:bikes,id',
            'pickup_station_id' => 'required|exists:stations,id',
        ]);

        $bike = Bike::findOrFail($request->bike_id);

        // Kiểm tra xe còn khả dụng
        if ($bike->status !== 'normal' || is_null($bike->station_id) || $bike->isRented()) {
            return back()->withErrors(['msg' => 'Xe không còn khả dụng, vui lòng chọn xe khác.']);
        }

        // Tạo bản ghi thuê xe
        Rental::create([
            'user_id'           => $user->id,
            'bike_id'           => $bike->id,
            'pickup_station_id' => $request->pickup_station_id,
            'rented_at'         => now(),
            'price'             => 200,
            'status'            => 'active',
        ]);

        // Xe rời khỏi trạm (station_id = null)
        $bike->update(['station_id' => null]);

        return redirect()->route('rental.rentForm')->with('success', 'Thuê xe thành công! Chúc bạn đi vui.');
    }

    public function returnForm()
    {
        $user = Auth::user();
        $activeRental = $user->activeRental()->with(['bike', 'pickupStation'])->first();

        if (!$activeRental) {
            return redirect()->route('home')->with('error', 'Bạn không có xe đang thuê.');
        }

        return view('rental.return', compact('activeRental'));
    }

    public function returnBike(Request $request)
    {
        $user         = Auth::user();
        $activeRental = $user->activeRental;

        if (!$activeRental) {
            return redirect()->route('home');
        }

        $request->validate([
            'return_station_id' => 'required|exists:stations,id',
        ]);

        $returnStation = Station::findOrFail($request->return_station_id);

        // Kiểm tra trạm còn chỗ không
        $parked = $returnStation->bikes()->whereNotNull('station_id')->count();
        if ($parked >= $returnStation->slots) {
            return back()->withErrors(['msg' => 'Trạm đã đầy chỗ, vui lòng chọn trạm khác.']);
        }

        // Tính số phút và tiền
        $mins        = now()->diffInMinutes($activeRental->rented_at);
        $totalAmount = $mins * $activeRental->price;

        // Cập nhật bản ghi thuê xe
        $activeRental->update([
            'return_station_id' => $returnStation->id,
            'return_at'       => now(),
            'duration_mins'     => $mins,
            'total_amount'      => $totalAmount,
            'status'            => 'returned',
        ]);

        // Xe về trạm mới
        $activeRental->bike->update(['station_id' => $returnStation->id]);

        // Chuyển đến trang đánh giá
        return redirect()->route('review.formRental', $activeRental->id);
    }

    public function history()
    {
        $rentals = Rental::with(['bike', 'pickupStation', 'returnStation'])
            ->where('user_id', Auth::id())
            ->where('status', 'returned')
            ->orderByDesc('rented_at')
            ->paginate(10);

        return view('rental.history', compact('rentals'));
    }

    /**
     * Trạm cho trang thuê xe:
     * - Lọc theo ward_code
     * - Không hiện trạm hết xe hoặc bảo trì
     */
    public function stationsForRental(Request $request)
    {
        $wardCode = $request->ward_code;

        $stations = Station::where('ward_code', $wardCode)
            ->where('status', 'active')
            ->withCount([
                'bikes as available' => fn($q) => $q
                    ->where('status', 'normal')
                    ->whereNotNull('station_id')
                    ->whereNotIn('id', fn($q2) =>
                        $q2->select('bike_id')->from('rentals')->where('status', 'active')
                    ),
            ])
            ->having('available', '>', 0)
            ->get([
                'id',
                'name',
                'status',
                'total_slots'
            ]);

        return response()->json($stations);
    }

    /**
     * Xe trong trạm cho dropdown biển số:
     * - Không hiện xe đang sửa hoặc đang được thuê
     */
    public function bikesInStation($id)
    {
        $bikes = Bike::where('station_id', $id)
            ->where('status', 'normal')
            ->whereNotIn('id', fn($q) =>
                $q->select('bike_id')->from('rentals')->where('status', 'active')
            )
            ->get(['id', 'plate_number']);

        return response()->json($bikes);
    }

    /**
     * Trạm cho trang trả xe:
     * - Lọc theo ward_code
     * - Không hiện trạm hết chỗ hoặc bảo trì
     */
    // public function stationsForReturn(Request $request)
    // {
    //     $wardCode = $request->ward_code;

    //     $stations = Station::where('ward_code', $wardCode)
    //         ->where('status', 'active')
    //         ->withCount(['bikes as parked' => fn($q) => $q->whereNotNull('station_id')])
    //         ->get()
    //         ->filter(fn($s) => $s->parked < $s->total_slots)
    //         ->values()
    //         ->map(fn($s) => ['id' => $s->id, 'name' => $s->name]);

    //     return response()->json($stations);
    // }
    public function stationsForReturn(Request $request)
{
    $stations = Station::where('ward_code', $request->ward_code)->get();

    return response()->json([
        'ward_code' => $request->ward_code,
        'count' => $stations->count(),
        'data' => $stations
    ]);
}
}
