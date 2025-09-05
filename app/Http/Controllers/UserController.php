<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ✅ Оставляем как было
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // ✅ Для админа: вывод всех пользователей
    public function allUsers(Request $request)
    {
        $authUser = $request->user();

        if ($authUser->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $users = User::all()->toArray();
        return response()->json($users);
    }

    // ✅ Для админа: найти пользователя по ID
    public function findById($id, Request $request)
    {
        $authUser = $request->user();

        if ($authUser->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user->toArray());
    }
}
