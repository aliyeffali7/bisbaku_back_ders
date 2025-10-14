<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str; // NEW

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['user', 'course'])->get();
        return response()->json($certificates->toArray());
    }

    public function download(Request $request, $id)
    {
        $certificate = Certificate::with('course')->findOrFail($id);

        // Авторизация: владелец или админ (подправь под свою логику)
        $user = $request->user();
        if (!$user || ($user->id !== (int)$certificate->user_id && !$user->is_admin)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $path = $certificate->image;
        if (!$path || !Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
        $courseTitle = optional($certificate->course)->title ?? 'sertifikat';
        $safeTitle = Str::slug($courseTitle, '-');
        $filename = ($safeTitle ?: 'sertifikat') . '_sertifikat.' . $ext;

        $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
        $content = Storage::disk('public')->get($path);

        // ВАЖНО: expose Content-Disposition и дать CORS
        return response($content, 200, [
            'Content-Type' => $mime,
            'Content-Length' => (string) strlen($content),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
            'Access-Control-Allow-Origin' => $request->headers->get('Origin') ?: '*',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $request->file('image')->store('certificates/images', 'public');

        $certificate = Certificate::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'image' => $imagePath,
            'deadline' => Carbon::now()->addYears(3),
            'status' => true,
        ]);

        return response()->json($certificate->toArray(), 201);
    }

    public function show($id)
    {
        $certificate = Certificate::with(['user', 'course'])->findOrFail($id);
        return response()->json($certificate->toArray());
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['user_id', 'course_id']);

        if ($request->hasFile('image')) {
            if ($certificate->image) {
                Storage::disk('public')->delete($certificate->image);
            }
            $data['image'] = $request->file('image')->store('certificates/images', 'public');
        }

        $certificate->update($data);

        return response()->json($certificate->toArray());
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->image) {
            Storage::disk('public')->delete($certificate->image);
        }

        $certificate->delete();

        return response()->json(['message' => 'Certificate deleted']);
    }
}
