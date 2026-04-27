<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class AdminStationController extends Controller
{
    public function index()
    {
        $stations = Station::withCount('bikes as bike_count')
            ->withAvg('reviews as avg_rating', 'station_rating')
            ->paginate(10);

        return view('admin.stations.index', compact('stations'));
    }

    public function show($id)
    {
        $station   = Station::with(['reviews.user'])->findOrFail($id);
        $bikeCount = $station->bikes()->count();
        $emptySlot = $station->slots - $bikeCount;
        return view('admin.stations.show', compact('station', 'bikeCount', 'emptySlot'));
    }

    public function create()
    {
        return view('admin.stations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'ward_code' => 'required',
            'address'   => 'required',
            'slots'     => 'required|integer|min:1',
            'status'    => 'required|in:active,maintenance',
        ]);

        Station::create($request->only('name', 'ward_code', 'address', 'slots', 'status'));
        return redirect()->route('admin.stations.index')->with('success', 'Thêm trạm thành công');
    }

    public function edit($id)
    {
        $station = Station::findOrFail($id);
        return view('admin.stations.edit', compact('station'));
    }

    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);
        $request->validate([
            'name'    => 'required',
            'address' => 'required',
            'slots'   => 'required|integer|min:1',
            'status'  => 'required|in:active,maintenance',
        ]);

        $station->update($request->only('name', 'address', 'slots', 'status'));
        return redirect()->route('admin.stations.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Station::findOrFail($id)->delete();
        return redirect()->route('admin.stations.index')->with('success', 'Đã chuyển vào thùng rác');
    }

    public function bin()
    {
        $stations = Station::onlyTrashed()->paginate(10);
        return view('admin.stations.bin', compact('stations'));
    }

    public function restore($id)
    {
        Station::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.stations.index')->with('success', 'Khôi phục thành công');
    }

    public function forceDelete($id)
    {
        Station::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Xóa thành công');
    }

    public function bulk(Request $request)
    {
        $ids    = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) return back()->with('error', 'Chưa chọn trạm nào');

        match($action) {
            'restore'      => Station::withTrashed()->whereIn('id', $ids)->restore(),
            'force-delete' => Station::withTrashed()->whereIn('id', $ids)->forceDelete(),
            'soft-delete'  => Station::whereIn('id', $ids)->delete(),
            default        => null
        };

        return back()->with('success', 'Thành công');
    }
}
