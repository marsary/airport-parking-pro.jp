<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarMakersController extends Controller
{
    public function cars(Request $request, $carMakerId)
    {
        if($carMakerId == 'all' && $request->query('default_all', false)) {
            $cars = Car::all();
        } else {
            $cars = Car::where('car_maker_id', $carMakerId)->get();
        }


        return response()->json([
            'success' => true,
            'data' => ['cars' => $cars],
         ]);
    }
}
