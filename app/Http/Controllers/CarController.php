<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $cars = Car::all();
        return response()->json($cars);
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        if ($request->has('name') && $request->filled('name')) {
            $car->name = $request->input('name');
        }


        if ($request->has('description') && $request->filled('description')){
            $car->description = $request->input('description');
        }

        if ($request->has('image_url')) {
            $car->image_url = $request->input('image_url');
        }

        $car->save();

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $car,
            'passedData' => $request->all(),
            'request' => $request
        ]);
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if ($car) {
            $car->delete();

            return response()->json([
                'success' => true,
                'message' => 'Car deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Car not found.',
            ], 404);
        }
    }
}
