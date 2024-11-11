<?php

namespace App\Http\Controllers;

use App\Models\AllFeature; // Ganti dengan model yang sesuai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllFeaturesController extends Controller
{
    // Pastikan hanya 'super_admin' yang bisa mengakses
    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil data semua fitur
        $features = AllFeature::all(); // Ganti dengan model dan data yang sesuai

        return view('all_features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('all_features.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Simpan fitur baru
        AllFeature::create($request->all());

        return redirect()->route('all_features.index')
                         ->with('success', 'Feature created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AllFeature  $allFeature
     * @return \Illuminate\Http\Response
     */
    public function show(AllFeature $allFeature)
    {
        return view('all_features.show', compact('allFeature'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AllFeature  $allFeature
     * @return \Illuminate\Http\Response
     */
    public function edit(AllFeature $allFeature)
    {
        return view('all_features.edit', compact('allFeature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AllFeature  $allFeature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AllFeature $allFeature)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update fitur
        $allFeature->update($request->all());

        return redirect()->route('all_features.index')
                         ->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AllFeature  $allFeature
     * @return \Illuminate\Http\Response
     */
    public function destroy(AllFeature $allFeature)
    {
        // Hapus fitur
        $allFeature->delete();

        return redirect()->route('all_features.index')
                         ->with('success', 'Feature deleted successfully.');
    }
}
