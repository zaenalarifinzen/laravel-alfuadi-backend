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
        $type_menu = 'dashboard.exercises.exercise-level';

        return view('pages.dashboard.exercise-level.index', compact('levels', 'type_menu'));
    }

    /**
     * Display the user-facing level list page.
     */
    public function userIndex()
    {
        $exerciseLevel = ExerciseLevel::orderBy('level_number', 'asc')->get();
        $type_menu = 'exercise';

        return view('pages.exercise.exercise-level.index', compact('exerciseLevel', 'type_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dashboard.exercise-level.create', [
            'type_menu' => 'exercise',
            'mode' => 'create'
        ]);
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
            return redirect()
                ->route('dashboard.exercise-levels.index')
                ->with('success', $request->name . ' berhasil ditambahkan');
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
        $type_menu = 'exercise';

        return view('pages.dashboard.exercise-level.edit', compact('level', 'type_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseLevelRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $level = ExerciseLevel::findOrFail($id);
            $level->update($data);
            
            return redirect()
                ->route('dashboard.exercise-levels.index')
                ->with('success', '"' . $level['name'] . '" berhasil diperbarui');
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
        return redirect()
            ->back()
            ->with('success', '"' . $exerciseLevel['name'] . '" berhasil dihapus');
    }

    /**
     * Activate the resource
     */
    public function activate(string $exerciseId)
    {
        return $this->setActiveStatus($exerciseId, true);
    }

    /**
     * Deactivate the resource
     */
    public function deactivate(string $exerciseId)
    {
        return $this->setActiveStatus($exerciseId, false);
    }

    /**
     * Status Setter
     */
    private function setActiveStatus(string $exerciseId, bool $status) {
        $exerciseLevel = ExerciseLevel::findOrFail($exerciseId);
        $exerciseLevel->update(['is_active' => $status]);
        $action = $status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "\"{$exerciseLevel->name}\" berhasil {$action}");
    }
}
