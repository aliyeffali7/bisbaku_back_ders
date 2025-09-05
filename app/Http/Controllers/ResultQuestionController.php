<?php

namespace App\Http\Controllers;

use App\Models\ResultQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $resultQuestions = ResultQuestion::with('result')->get();
        return response()->json($resultQuestions);
    }

    public function show($id)
    {
        $resultQuestion = ResultQuestion::with('result')->findOrFail($id);
        return response()->json($resultQuestion);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'result_id'      => 'required|exists:results,id',
            'title'          => 'required|string|max:255',
            'correct_answer' => 'required|string|max:255',
            'user_answer'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = $request->correct_answer === $request->user_answer;

        $resultQuestion = ResultQuestion::create([
            'result_id'      => $request->result_id,
            'title'          => $request->title,
            'correct_answer' => $request->correct_answer,
            'user_answer'    => $request->user_answer,
            'status'         => $status,
        ]);

        return response()->json($resultQuestion, 201);
    }

    public function update(Request $request, $id)
    {
        $resultQuestion = ResultQuestion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'          => 'sometimes|required|string|max:255',
            'correct_answer' => 'sometimes|required|string|max:255',
            'user_answer'    => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['title', 'correct_answer', 'user_answer']);

        if (isset($data['correct_answer']) || isset($data['user_answer'])) {
            $correct = $data['correct_answer'] ?? $resultQuestion->correct_answer;
            $user    = $data['user_answer'] ?? $resultQuestion->user_answer;
            $data['status'] = $correct === $user;
        }

        $resultQuestion->update($data);

        return response()->json($resultQuestion);
    }

    public function destroy($id)
    {
        $resultQuestion = ResultQuestion::findOrFail($id);
        $resultQuestion->delete();

        return response()->json(['message' => 'ResultQuestion deleted']);
    }
}
