<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ApplyStatusNotification;
use App\Notifications\NewApplicationNotification;
use Illuminate\Support\Facades\Notification;

class ApplyController extends Controller
{
    /**
     * Получить список заявок
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $applies = Apply::with('course', 'user')->get();
        } else {
            $applies = Apply::with('course')
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json($applies);
    }

    /**
     * Отправить заявку
     */
    public function store(Request $request)
        {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'email' => 'required|email',
                'phone_number' => 'required|string|max:20',
                'message' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $apply = Apply::create([
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            // Отправляем уведомление на office@bisbaku.az
            Notification::route('mail', 'office@bisbaku.az')
                ->notify(new NewApplicationNotification($apply));

            return response()->json($apply, 201);
        }

        /**
         * Просмотр заявки
         */
        public function show($id)
        {
            $apply = Apply::findOrFail($id);
            $user = Auth::user();

            if ($user->role !== 'admin' && $apply->user_id !== $user->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            return response()->json($apply);
        }

    /**
     * Обновить заявку (только для админа)
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $apply = Apply::with('user', 'course')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'in:pending,approved,rejected',
            'message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $apply->update($request->only(['status', 'message']));

        // Отправляем уведомление пользователю
        if (in_array($apply->status, ['approved', 'rejected'])) {
            $apply->user->notify(new ApplyStatusNotification($apply));
        }

        return response()->json($apply);
    }


    /**
     * Удалить заявку (только для админа)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $apply = Apply::findOrFail($id);
        $apply->delete();

        return response()->json(['message' => 'Apply deleted successfully']);
    }
}
