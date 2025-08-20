<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $companies = $query->paginate(10)->withQueryString();
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        $validated = $request->validated();
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }
        $company = Company::create($validated);
        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'subject_type' => 'Company',
            'subject_id' => $company->id,
            'description' => 'Created company #' . $company->id,
        ]);
        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        $employees = $company->employees()->paginate(10);
        return view('companies.show', compact('company', 'employees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        $company = Company::findOrFail($id);
        $validated = $request->validated();
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            try {
                // Delete old logo if it exists
                if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                    Storage::disk('public')->delete($company->logo);
                }
                
                // Store the new logo with original filename
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $logoPath = $file->storeAs('logos', $filename, 'public');
                
                if (!$logoPath) {
                    return back()->with('error', 'Failed to upload logo. Please try again.');
                }
                
                $validated['logo'] = $logoPath;
            } catch (\Exception $e) {
                return back()->with('error', 'Error uploading logo: ' . $e->getMessage());
            }
        }
        $company->update($validated);
        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'subject_type' => 'Company',
            'subject_id' => $company->id,
            'description' => 'Updated company #' . $company->id,
        ]);
        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully!');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        
        // Check if company has employees
        if ($company->employees()->count() > 0) {
            return redirect()->route('companies.index')
                ->with('error', 'Cannot delete company. It has employees assigned to it. Please remove all employees first.');
        }
        
        // Delete logo file if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        
        $company->delete();
        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'subject_type' => 'Company',
            'subject_id' => $company->id,
            'description' => 'Deleted company #' . $company->id,
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
