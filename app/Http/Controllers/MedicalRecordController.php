<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.medical-records.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.medical-records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'address3' => 'nullable|string|max:255',
            'address4' => 'nullable|string|max:255',
            'address5' => 'nullable|string|max:255',
            'address6' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'next_of_kin' => 'nullable|string|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_alt_phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'allergies' => 'nullable|string',
            'dietary_requirement' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'current_medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        // Here you would typically save to database
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Medical record saved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('pages.medical-records.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.medical-records.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
