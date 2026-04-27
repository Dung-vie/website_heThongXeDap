<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\Station;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    //
    // GET /admin/bikes
    public function index()
    {
        $bikes = Bike::with('station')
            ->withAvg('reviews as avg_rating', 'bike_rating')
            ->paginate(10);

        return view('admin.bikes.index', compact('bikes'));
    }

    // GET /admin/bikes/{id}
    public function show($id)
    {
        $bike = Bike::with(['station', 'reviews.user'])->findOrFail($id);
        return view('admin.bikes.show', compact('bike'));
    }

    // GET /admin/bikes/create
    public function create()
    {
        $stations = Station::where('status', 'active')->get();
        return view('admin.bikes.create', compact('stations'));
    }

    // POST /admin/bikes
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:bikes,plate_number',
            'station_id'   => 'required|exists:stations,id',
            'status'       => 'required|in:normal,repair',
        ]);

        Bike::create($request->only('plate_number', 'station_id', 'status'));
        return redirect()->route('admin.bikes.index')->with('success', 'Thêm xe thành công');
    }

    // GET /admin/bikes/{id}/edit
    public function edit($id)
    {
        $bike     = Bike::findOrFail($id);
        $stations = Station::where('status', 'active')->get();
        return view('admin.bikes.edit', compact('bike', 'stations'));
    }

    // PUT /admin/bikes/{id}
    public function update(Request $request, $id)
    {
        $bike = Bike::findOrFail($id);
        $request->validate([
            'plate_number' => "required|unique:bikes,plate_number,{$id}",
            'station_id'   => 'nullable|exists:stations,id',
            'status'       => 'required|in:normal,repair',
        ]);

        $bike->update($request->only('plate_number', 'station_id', 'status'));
        return redirect()->route('admin.bikes.index')->with('success', 'Cập nhật thành công');
    }

    // DELETE /admin/bikes/{id} → soft delete
    public function destroy($id)
    {
        Bike::findOrFail($id)->delete();
        return redirect()->route('admin.bikes.index')->with('success', 'Đã chuyển vào thùng rác');
    }

    // GET /admin/bikes/bin
    public function bin()
    {
        $bikes = Bike::onlyTrashed()->paginate(10);
        return view('admin.bikes.bin', compact('bikes'));
    }

    // POST /admin/bikes/{id}/restore
    public function restore($id)
    {
        Bike::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.bikes.index')->with('success', 'Khôi phục thành công');
    }

    // DELETE /admin/bikes/{id}/force
    public function forceDelete($id)
    {
        Bike::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Xóa thành công');
    }

    // POST /admin/bikes/bulk
    public function bulk(Request $request)
    {
        $ids    = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) return back()->with('error', 'Chưa chọn xe nào');

        match($action) {
            'restore'      => Bike::withTrashed()->whereIn('id', $ids)->restore(),
            'force-delete' => Bike::withTrashed()->whereIn('id', $ids)->forceDelete(),
            'soft-delete'  => Bike::whereIn('id', $ids)->delete(),
            default        => null
        };

        $msg = match($action) {
            'restore'      => 'Khôi phục thành công',
            'force-delete' => 'Xóa thành công',
            'soft-delete'  => 'Đã chuyển vào thùng rác',
            default        => 'Thành công'
        };

        $redirect = $action === 'restore' ? route('admin.bikes.index') : back()->getTargetUrl();
        return redirect($redirect)->with('success', $msg);
    }
}
