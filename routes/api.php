<?php

use App\Http\Controllers\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/cars', function (Illuminate\Http\Request $request) {
    // Перевірка правильності вхідних даних
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|image',
    ]);

    // Збереження зображення
    $image = $request->file('image');
    $imagePath = $image->store('public/images/cars');
    $imageUrl = Storage::url($imagePath);

    // Створення запису в базі даних
    $car = new App\Models\Car();
    $car->name = $validatedData['name'];
    $car->description = $validatedData['description'];
    $car->image_url = $imageUrl;
    $car->save();

    // Повернення відповіді
    return response()->json([
        'success' => true,
        'message' => 'Car created successfully.',
        'data' => $car,
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
