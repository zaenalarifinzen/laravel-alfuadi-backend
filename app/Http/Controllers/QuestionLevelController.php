<?php

namespace App\Http\Controllers;

use App\Models\QuestionLevel;
use Illuminate\Http\Request;

class QuestionLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionLevel = QuestionLevel::orderBy('level_number', 'asc')
            ->get();
        
        return view('pages.exercise.question-level.index', ['questionLevel' => $questionLevel, 'type_menu' => '']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.exercise.question-level.create', ['type_menu' => 'exercise']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:question_levels,name',
            'level_number' => 'required|integer|unique:question_levels,level_number',
            'description' => 'nullable|string',
        ]);

        try {
            QuestionLevel::create($validated);
            return redirect()->route('exercise-level.index')->with('success', 'Level berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan level: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
