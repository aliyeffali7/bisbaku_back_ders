<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function __construct()
    {
        // Только админ может создавать/обновлять/удалять
        $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $answers = Answer::with('question')->get();
        return response()->json($answers);
    }

    public function show($id)
    {
        $answer = Answer::with('question')->findOrFail($id);
        return response()->json($answer);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'status'      => 'required|boolean',
            'question_id' => 'required|exists:questions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $answer = Answer::create($request->only(['title', 'status', 'question_id']));

        return response()->json($answer, 201);
    }

    public function update(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'status'      => 'sometimes|required|boolean',
            'question_id' => 'sometimes|required|exists:questions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $answer->update($request->only(['title', 'status', 'question_id']));

        return response()->json($answer);
    }

    public function destroy($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();

        return response()->json(['message' => 'Answer deleted']);
    }
}
