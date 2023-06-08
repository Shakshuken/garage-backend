<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function indexId(): \Illuminate\Http\JsonResponse
    {
        $drivers = Driver::all();
        return response()->json($drivers);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $drivers = Driver::all(['id', 'first_name', 'last_name', 'email', 'phone_number', 'image_url']);
        return response()->json($drivers);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $driver = Driver::findOrFail($id);

        if ($request->has('first_name') && $request->filled('first_name')) {
            $driver->first_name = $request->input('first_name');
        }

        if ($request->has('last_name') && $request->filled('last_name')) {
            $driver->last_name = $request->input('last_name');
        }

        if ($request->has('phone_number') && $request->filled('phone_number')) {
            $driver->phone_number = $request->input('phone_number');
        }

        if ($request->has('email') && $request->filled('email')) {
            $driver->email = $request->input('email');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('public/images/drivers');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($imagePath);
            $driver->image_url = $imageUrl;
        }

        if ($request->has('birth_date') && $request->filled('birth_date')) {
            $birthDate = date('Y-m-d', strtotime($request->input('birth_date')));
            $driver->birth_date = $birthDate;
        }

        if ($request->has('issue_date') && $request->filled('issue_date')) {
            $issueDate = date('Y-m-d', strtotime($request->input('issue_date')));
            $driver->issue_date = $issueDate;
        }

        if ($request->has('expiration_date') && $request->filled('expiration_date')) {
            $expirationDate = date('Y-m-d', strtotime($request->input('expiration_date')));
            $driver->expiration_date = $expirationDate;
        }

        if ($request->has('authority') && $request->filled('authority')) {
            $driver->authority = $request->input('authority');
        }

        if ($request->has('license_num') && $request->filled('license_num')) {
            $driver->license_num = $request->input('license_num');
        }

        if ($request->has('category') && $request->filled('category')) {
            $driver->category = $request->input('category');
        }

        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Driver updated successfully',
            'data' => $driver,
        ]);
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);

        if ($driver) {
            $driver->delete();

            return response()->json([
                'success' => true,
                'message' => 'Driver deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found.',
            ], 404);
        }
    }
}
