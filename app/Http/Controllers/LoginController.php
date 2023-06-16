<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Аутентифікувати користувача
        if (Auth::attempt($request->only('email', 'password'))) {
            // Аутентифікація успішна

            // Отримати ідентифікатор користувача
            $userId = Auth::id();

            // Отримати IP-адресу користувача
            $ipAddress = $request->ip();

            // Отримати агента користувача
            $userAgent = $request->userAgent();

            // Створити новий запис в таблиці sessions
            $sessionId = Str::random(40); // Генерувати унікальний ідентифікатор сесії
            $payload = ''; // Серіалізовані дані сесії
            $lastActivity = time(); // Отримати поточний час

            DB::table('sessions')->insert([
                'id' => $sessionId,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'payload' => $payload,
                'last_activity' => $lastActivity,
            ]);

            // Зберегти ідентифікатор сесії у cookie або десь інде
            // для подальшого використання при автентифікації

            // Повернути успішний відгук або перенаправити користувача на сторінку за необхідністю
            return response()->json(['message' => 'Аутентифікація успішна']);
        } else {
            // Аутентифікація неуспішна

            // Повернути помилку аутентифікації або перенаправити користувача на сторінку за необхідністю
            return response()->json(['error' => 'Неправильні облікові дані'], 401);
        }
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->forget('user_id');


        return response()->json(['message' => 'Вихід успішний']);
    }
}
