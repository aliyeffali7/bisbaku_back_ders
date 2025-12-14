<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function __construct()
    {
        // Только админ может создавать/обновлять/удалять
        $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        // Загружаем курс и компанию
        $exams = Exam::with(['course', 'company'])->get();
        return response()->json($exams);
    }

    public function show($id)
    {
        // Загружаем курс и компанию
        $exam = Exam::with(['course', 'company'])->findOrFail($id);
        return response()->json($exam);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'course_id'        => 'required|exists:courses,id',
            'score'            => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:1',
            // ✅ Добавляем валидацию для company_id
            'company_id'       => 'nullable|exists:companies,id', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exam = Exam::create($request->only([
            'title',
            'description',
            'course_id',
            'score',
            'duration_minutes',
            // ✅ Добавляем company_id
            'company_id',
        ]));

        return response()->json($exam, 201);
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'            => 'sometimes|required|string|max:255',
            'description'      => 'sometimes|required|string',
            'course_id'        => 'sometimes|required|exists:courses,id',
            'score'            => 'sometimes|required|integer|min:0',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            // ✅ Добавляем валидацию для company_id
            'company_id'       => 'sometimes|nullable|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // ✅ Включаем company_id в список обновляемых полей
        $exam->update($request->only([
            'title',
            'description',
            'course_id',
            'score',
            'duration_minutes',
            'company_id',
        ]));

        return response()->json($exam);
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'Exam deleted']);
    }
}