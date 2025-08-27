<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if ($request->expectsJson()) {
            return null; // Не перенаправляем для API-запросов
        }

        return route('login'); // Для веб-запросов (если нужно)
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        parent::unauthenticated($request, $guards);
    }
}