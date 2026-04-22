<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Station;
use App\Models\TopBiker;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $totalBikes = Bike::query()->count();
        $totalStations = Station::query()->count();

        $topStations = Station::withAvg('reviews as avg_rating', 'station_rating')
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();

        $topStations->each(function ($station) {
            $station->setRelation('reviews', $station->reviews()->latest()->limit(3)->get());
        });

        // dd($topStations);

        $month = now()->subMonth()->month;
        $year  = now()->subMonth()->year;

        $topBikers = TopBiker::with('user')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('rank')
            ->get();

        return view('home', compact('totalBikes', 'totalStations', 'topStations', 'topBikers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
