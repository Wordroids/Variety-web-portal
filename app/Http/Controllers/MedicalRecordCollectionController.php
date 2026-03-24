<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\MedicalRecordCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $events = $user->hasRole("Super Admin")
            ? Event::latest()->get()
            : $user->events()->latest()->get();

        return view(
            "pages.medical-record-collections.index",
            compact("events"),
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.medical-record-collections.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecordCollection $medicalRecordCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecordCollection $medicalRecordCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        MedicalRecordCollection $medicalRecordCollection,
    ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecordCollection $medicalRecordCollection)
    {
        //
    }
}
