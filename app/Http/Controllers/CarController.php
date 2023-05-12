<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $cars = Car::all();
        return response()->json($cars);
    }
}
