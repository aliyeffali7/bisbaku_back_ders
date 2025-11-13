<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses->toArray());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'full_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'education_document' => 'required|mimes:pdf|max:10000',
            'contract_document' => 'required|mimes:pdf|max:10000',
            // Правило валидации: certificate_image необязательно, должно быть изображением
            'certificate_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $request->file('image')->store('courses/image', 'public');
        $educationDocPath = $request->file('education_document')->store('courses/documents', 'public');
        $contractDocPath = $request->file('contract_document')->store('courses/documents', 'public');
        
        // Обработка загрузки certificate_image, если оно есть
        $certificateImagePath = null;
        if ($request->hasFile('certificate_image')) {
            $certificateImagePath = $request->file('certificate_image')->store('courses/certificate', 'public');
        }

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'full_description' => $request->full_description,
            'price' => $request->price,
            'image'=> $imagePath,
            'education_document' => $educationDocPath,
            'contract_document' => $contractDocPath,
            'certificate_image' => $certificateImagePath // Добавлено
        ]);

        return response()->json($course->toArray(), 201); 
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course->toArray());
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'full_description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'education_document' => 'sometimes|mimes:pdf|max:10000',
            'contract_document' => 'sometimes|mimes:pdf|max:10000',
            // Правило валидации для обновления (sometimes|nullable)
            'certificate_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        if($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        $data = $request->only(['title', 'description', 'full_description', 'price']);

        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses/images', 'public');
        }

        if ($request->hasFile('education_document')) {
            if ($course->education_document) {
                Storage::disk('public')->delete($course->education_document);
            }
            $data['education_document'] = $request->file('education_document')->store('courses/documents', 'public');
        }

        if ($request->hasFile('contract_document')) {
            if ($course->contract_document) {
                Storage::disk('public')->delete($course->contract_document);
            }
            $data['contract_document'] = $request->file('contract_document')->store('courses/documents', 'public');
        }
        
        // Обработка обновления certificate_image
        if ($request->hasFile('certificate_image')) {
            if ($course->certificate_image) {
                Storage::disk('public')->delete($course->certificate_image);
            }
            $data['certificate_image'] = $request->file('certificate_image')->store('courses/certificate', 'public');
        }
        // Если поле отправлено как null (например, для удаления существующего изображения)
        // ВАЖНО: для работы этого, в запросе должен быть отправлен `certificate_image` = null
        else if ($request->has('certificate_image') && $request->get('certificate_image') === null) {
            if ($course->certificate_image) {
                Storage::disk('public')->delete($course->certificate_image);
            }
            $data['certificate_image'] = null;
        }

        $course->update($data);

        return response()->json($course->toArray());
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        if($course->education_document) {
            Storage::disk('public')->delete($course->education_document);
        }
        if($course->contract_document) {
            Storage::disk('public')->delete($course->contract_document);
        }
        // Удаление файла сертификата при удалении курса
        if($course->certificate_image) {
            Storage::disk('public')->delete($course->certificate_image);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted']);
    }
}