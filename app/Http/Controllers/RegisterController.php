<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Загружает связь 'company', если она существует на модели User.
     * Это временная мера, чтобы предотвратить сбой 500, если связь не определена.
     * @param User $user
     * @return User
     */
    protected function loadCompany(User $user): User
    {
        // Проверяем, существует ли метод 'company' в модели User
        if (method_exists($user, 'company')) {
            // Если да, загружаем связь.
            $user->load('company');
        }
        return $user;
    }

    /**
     * Регистрация обычного пользователя (роль: student).
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'company_id' => 'nullable|integer|exists:companies,id', 
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'first_name' => $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'student', 
        ];

        if ($request->filled('company_id')) {
            $data['company_id'] = $request->company_id;
        }

        $user = User::create($data);
        
        // 🔄 Используем вспомогательный метод для загрузки связи
        $user = $this->loadCompany($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            ], 201);
    }

    /**
     * Регистрация пользователя с ролью "admin".
     */
    public function registerAdmin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'company_id' => 'nullable|integer|exists:companies,id', 
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'first_name' => $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'admin', 
        ];

        if ($request->filled('company_id')) {
            $data['company_id'] = $request->company_id;
        }
        
        $user = User::create($data);
        
        // 🔄 Используем вспомогательный метод для загрузки связи
        $user = $this->loadCompany($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Admin user successfully registered'
            ], 201);
    }
}