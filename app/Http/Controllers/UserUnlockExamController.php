<?php

namespace App\Http\Controllers;

use App\Models\UserUnlockExam;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserUnlockExamController extends Controller
{
    public function __construct()
    {
        // Только авторизованные пользователи могут начинать экзамен
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $unlocks = UserUnlockExam::with(['user', 'exam'])->get();
        return response()->json($unlocks);
    }

    public function show($id)
    {
        $unlock = UserUnlockExam::with(['user', 'exam'])->findOrFail($id);
        return response()->json($unlock);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exam = Exam::findOrFail($request->exam_id);
        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addMinutes($exam->duration_minutes);

        $unlock = UserUnlockExam::create([
            'user_id'    => $request->user_id,
            'exam_id'    => $exam->id,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        return response()->json($unlock, 201);
    }

    public function update(Request $request, $id)
    {
        $unlock = UserUnlockExam::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'sometimes|required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('exam_id')) {
            $exam = Exam::findOrFail($request->exam_id);
            $startTime = Carbon::now();
            $endTime = $startTime->copy()->addMinutes($exam->duration_minutes);

            $unlock->update([
                'exam_id'    => $exam->id,
                'start_time' => $startTime,
                'end_time'   => $endTime,
            ]);
        }

        return response()->json($unlock);
    }

    public function destroy($id)
    {
        $unlock = UserUnlockExam::findOrFail($id);
        $unlock->delete();

        return response()->json(['message' => 'Unlock record deleted']);
    }
}
