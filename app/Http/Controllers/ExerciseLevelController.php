<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseLevelRequest;
use App\Http\Requests\UpdateExerciseLevelRequest;
use App\Models\ExerciseLevel;
use Illuminate\Http\Request;

class ExerciseLevelController extends Controller
{
    /**
     * Display the admin listing of exercise levels.
     */
    public function index()
    {
        $levels = ExerciseLevel::orderBy('level_number', 'asc')->get();

        return view('pages.admin.exercise-level.index', ['levels' => $levels, 'type_menu' => 'admin.exercises.exercise-level']);
    }

    /**
     * Display the user-facing level list page.
     */
    public function userIndex()
    {
        $exerciseLevel = ExerciseLevel::orderBy('level_number', 'asc')->get();

        return view('pages.exercise.exercise-level.index', [
            'exerciseLevel' => $exerciseLevel,
            'type_menu' => 'exercise',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.exercise-level.create', ['type_menu' => 'exercise', 'mode' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseLevelRequest $request)
    {
        // Validasi input
        $data = $request->validated();
        $data['display_order'] = $data['level_number'] . 0; 

        try {
            ExerciseLevel::create($data);
            return redirect()->route('admin.exercise-levels.index')->with('success', $request->name . ' berhasil ditambahkan');
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
        $level = ExerciseLevel::findOrFail($id);
        return view('pages.admin.exercise-level.edit', ['level' => $level, 'type_menu' => 'exercise', 'mode' => 'edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseLevelRequest $request, string $id)
    {
        // Validasi input
        $data = $request->validated();

        try {
            $level = ExerciseLevel::findOrFail($id);
            $level->update($data);
            return redirect()->route('admin.exercise-levels.index')->with('success', 'Level berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui level: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExerciseLevel $exerciseLevel)
    {
        $exerciseLevel->delete();
        return redirect()->route('admin.exercise-levels.index')->with('success', '"' . $exerciseLevel['name'] . '" succesfully deleted');
    }

    /**
     * Custom Function
     */
    

}
