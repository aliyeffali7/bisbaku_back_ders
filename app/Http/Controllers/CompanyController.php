<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all(['id', 'title', 'image']);
        $companies->each(function ($company) {
            if ($company->image) {
                $company->image = Storage::url($company->image);
            }
        });
        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:companies,title',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('companies/image', 'public');
        }

        $company = Company::create([
            'title' => $request->title,
            'image' => $imagePath,
        ]);

        if ($company->image) {
            $company->image = Storage::url($company->image);
        }

        return response()->json($company->toArray(), 201);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255|unique:companies,title,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['title']);

        if ($request->hasFile('image')) {
            if ($company->image) {
                Storage::disk('public')->delete($company->image);
            }
            $data['image'] = $request->file('image')->store('companies/image', 'public');
        }
        else if ($request->has('image') && $request->get('image') === null) {
            if ($company->image) {
                Storage::disk('public')->delete($company->image);
            }
            $data['image'] = null;
        }

        $company->update($data);

        if ($company->image) {
            $company->image = Storage::url($company->image);
        }
        
        return response()->json($company->toArray());
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->image) {
            Storage::disk('public')->delete($company->image);
        }

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }
}