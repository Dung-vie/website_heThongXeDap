<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bike;
use Illuminate\Http\Request;


class AdminAuthController extends Controller
{
    public function index()
    {
        $bikes = Bike::with('station')
            ->withAvg('reviews as avg_rating', 'bike_rating')
            ->paginate(10);

        return view('admin.bikes.index', compact('bikes'));
    }

     public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        return redirect('/login');
    }

}
