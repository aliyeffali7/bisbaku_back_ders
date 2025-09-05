<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    public function __construct()
    {
        // только авторизованные пользователи работают с результатами
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $results = Result::with(['user', 'exam'])->get();
        return response()->json($results);
    }

    public function show($id)
    {
        $result = Result::with(['user', 'exam'])->findOrFail($id);
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'score'   => 'required|integer|min:0',
            'status'  => 'required|boolean',
            'exam_id' => 'required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = Result::create([
            'title'   => $request->title,
            'score'   => $request->score,
            'status'  => $request->status,
            'exam_id' => $request->exam_id,
            'user_id' => $request->user()->id, // текущий пользователь
        ]);

        return response()->json($result, 201);
    }

    public function update(Request $request, $id)
    {
        $result = Result::findOrFail($id);

        // можно редактировать только свои результаты
        if ($result->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'   => 'sometimes|required|string|max:255',
            'score'   => 'sometimes|required|integer|min:0',
            'status'  => 'sometimes|required|boolean',
            'exam_id' => 'sometimes|required|exists:exams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result->update($request->only(['title', 'score', 'status', 'exam_id']));

        return response()->json($result);
    }

    public function destroy(Request $request, $id)
    {
        $result = Result::findOrFail($id);

        // удалять может только владелец или админ
        if ($result->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $result->delete();

        return response()->json(['message' => 'Result deleted']);
    }
}
