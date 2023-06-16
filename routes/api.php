<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\DriverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Fortify;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/cars', [CarController::class, 'index']);
Route::get('/drivers', [DriverController::class, 'index']);


Route::post('/cars', function (Illuminate\Http\Request $request) {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|image',
    ]);

    $image = $request->file('image');
    $imagePath = $image->store('public/images/cars');
    $imageUrl = Storage::url($imagePath);

    $car = new App\Models\Car();
    $car->name = $validatedData['name'];
    $car->description = $validatedData['description'];
    $car->image_url = $imageUrl;
    $car->save();

    $data = json_decode($request->getContent());

    return response()->json([
        'success' => true,
        'message' => 'Car created successfully.',
        'data' => $car,
    ]);
});

Route::post('/drivers', function (Illuminate\Http\Request $request) {
    $validatedData = $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'phone_number' => 'required|string',
        'email' => 'required|email',
        'image' => 'required|image',
        'birth_date' => 'required|date',
        'issue_date' => 'required|date',
        'expiration_date' => 'required|date',
        'authority' => 'required|string',
        'license_num' => 'required|string',
        'category' => 'required|string',
    ]);

    $image = $request->file('image');
    $imagePath = $image->store('public/images/drivers');
    $imageUrl = Storage::url($imagePath);

    $driver = new App\Models\Driver();
    $driver->first_name = $validatedData['first_name'];
    $driver->last_name = $validatedData['last_name'];
    $driver->phone_number = $validatedData['phone_number'];
    $driver->email = $validatedData['email'];

    // Format birth_date
    $birthDate = date('Y-m-d', strtotime($validatedData['birth_date']));
    $driver->birth_date = $birthDate;

    // Format issue_date
    $issueDate = date('Y-m-d', strtotime($validatedData['issue_date']));
    $driver->issue_date = $issueDate;

    // Format expiration_date
    $expirationDate = date('Y-m-d', strtotime($validatedData['expiration_date']));
    $driver->expiration_date = $expirationDate;

    $driver->authority = $validatedData['authority'];
    $driver->license_num = $validatedData['license_num'];
    $driver->category = $validatedData['category'];

    $driver->image_url = $imageUrl;
    $driver->save();

    $data = json_decode($request->getContent());

    return response()->json([
        'success' => true,
        'message' => 'Driver created successfully.',
        'data' => $driver,
    ]);
});

Route::get('/drivers/{id}', function ($id) {
    $driver = App\Models\Driver::find($id);

    if ($driver) {
        return response()->json([
            'success' => true,
            'data' => $driver,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Driver not found.',
        ], 404);
    }
});

Route::get('/cars/{id}', function ($id) {
    $car = App\Models\Car::find($id);

    if ($car) {
        return response()->json([
            'success' => true,
            'data' => $car,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Car not found.',
        ], 404);
    }
});

Route::post('/cars/{id}', [CarController::class, 'update']);

Route::post('/drivers/{id}', [DriverController::class, 'update']);

Route::delete('/cars/{id}', [CarController::class, 'destroy']);

Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['middleware' => ['web']], function () {
    Route::post('/login', 'App\Http\Controllers\LoginController@login');
    Route::post('/logout', 'App\Http\Controllers\LoginController@logout');
});
