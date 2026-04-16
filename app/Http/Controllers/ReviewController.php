<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //
     /** Form đánh giá sau khi trả xe. */
     public function form(Rental $rental)
     {
         // Chỉ owner mới xem được
         abort_if($rental->user_id !== Auth::id(), 403);
         abort_if($rental->status !== 'returned', 403);
 
         // Nếu đã đánh giá rồi thì về trang chủ
         if ($rental->review) {
             return redirect()->route('home')->with('info', 'Bạn đã đánh giá chuyến đi này rồi.');
         }
 
         return view('form', compact('rental'));
     }
 
     /** Lưu đánh giá. */
     public function store(Request $request, Rental $rental)
     {
         abort_if($rental->user_id !== Auth::id(), 403);
 
         $request->validate([
             'bike_rating'     => 'required|integer|between:1,5',
             'bike_comment'    => 'nullable|max:200',
             'station_rating'  => 'required|integer|between:1,5',
             'station_comment' => 'nullable|max:200',
         ]);
 
         Review::create([
             'user_id'        => Auth::id(),
             'rental_id'      => $rental->id,
             'bike_id'        => $rental->bike_id,
             'station_id'     => $rental->return_station_id,
             'bike_rating'    => $request->bike_rating,
             'bike_comment'   => $request->bike_comment,
             'station_rating' => $request->station_rating,
             'station_comment'=> $request->station_comment,
         ]);
 
         return redirect()->route('home')->with('success', 'Cảm ơn bạn đã đánh giá!');
    }
}
