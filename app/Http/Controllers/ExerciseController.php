<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\UserAnswer;
use App\Models\Verse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Log;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::with('exerciseLevel')
            ->orderBy('level', 'asc')
            ->orderBy('display_order', 'asc')
            ->get();

        foreach ($exercises as $exercise) {
            $exercise->load('exerciseLevel');
        }

        $type_menu = 'admin.exercises.exercise';

        return view('pages.admin.exercise.index', compact('exercises', 'type_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = ExerciseLevel::orderBy('level_number', 'asc')->get();
        $type_menu = 'admin.exercises.exercise';

        return view('pages.admin.exercise.create', compact('levels', 'type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['type'] = 'analysis';
        $data['is_active'] = false;

        if (!isset($data['display_order'])) {
            $maxDisplayOrder = Exercise::where('level', $data['level'])->max('display_order');
            $data['display_order'] = $maxDisplayOrder ? $maxDisplayOrder + 1 : 1;
        }

        Exercise::create($data);
        return redirect()
            ->route('admin.exercises.index')
            ->with('success', '"' . $data['title'] . '" created succesfully');
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
        $exercise = Exercise::findOrFail($id);
        $levels = ExerciseLevel::orderBy('level_number', 'asc')->get();
        $type_menu = 'admin.exercises.exercise';

        return view('pages.admin.exercise.edit', compact('exercise', 'levels', 'type_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, string $id)
    {
        Log::info('Update Exercise Request: ', $request->all());
        $data = $request->validated();

        $exercise = Exercise::findOrFail($id);
        $exercise->update($data);

        return redirect()->route('admin.exercises.index')
            ->with('success', '"' . $data['title'] . '" succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return redirect()->back()->with('success', '"' . $exercise['title'] . '" succesfully deleted');
    }

    /**
     * CUSTOM FUNCTION
     */
    public function getExercise(Request $request, $level = null, $exerciseOrderNumber = null)
    {
        if ($level === 'alquran') {
            return $this->getQuranExercise($request, $level, $exerciseOrderNumber);
        } else {
            return $this->getBasicExercise($level, $exerciseOrderNumber);
        }
    }

    public function getBasicExercise($level = null, $exerciseOrderNumber = null)
    {
        $exerciseLevel = ExerciseLevel::where('slug', $level)->active()->first();
        $levelNumber = $exerciseLevel ? $exerciseLevel->level_number : (int) $level;

        $exercise = Exercise::with('verse')
            ->active()
            ->where('level', $levelNumber)
            ->where('display_order', $exerciseOrderNumber)
            ->first();

        $content = $exercise->content ?? null;

        if (!$exercise || !$content) {
            return response()->json([
                'success' => false,
                'message' => 'Exercise not found',
            ], 404);
        }

        if (auth()->check()) {
            $ua = UserAnswer::where('user_id', auth()->id())
                ->where('exercise_id', $exercise->id)
                ->where('passed', true)
                ->latest()
                ->first();

            $exercise->setAttribute('passed', $ua ? (bool) $ua->passed : false);
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $this->formatExerciseResponse($exercise),
        ], 200);
    }

    public function getQuranExercise(Request $request, $level = null, $exerciseOrderNumber = null)
    {
        try {
            $validLevels = ExerciseLevel::active()->pluck('slug')->toArray();

            if (!in_array($level, $validLevels)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid level',
                ], 400);
            }

            if ($request->filled('slug')) {
                $exerciseLevel = ExerciseLevel::where('slug', $request->query('slug'))->active()->first();

                if (!$exerciseLevel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid level',
                    ], 404);
                }
                $levelNumber = $exerciseLevel->level_number;
            } elseif ($request->filled('level')) {
                $levelNumber = (int) $request->query('level');
            } elseif ($level) {
                $exerciseLevel = ExerciseLevel::where('slug', $level)->active()->first();
                $levelNumber = $exerciseLevel ? $exerciseLevel->level_number : (int) $level;
            }

            $verse = null;
            $resolvedExerciseOrderNumber = $exerciseOrderNumber;

            if ($exerciseLevel->slug === 'alquran' && $exerciseOrderNumber) {
                $verse = Verse::with(['surah', 'wordGroups.words'])->find($exerciseOrderNumber);
                if (!$verse) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Verse not found',
                    ], 404);
                }
                $resolvedExerciseOrderNumber = $verse->id;
            } elseif ($exerciseLevel->slug === 'alquran' && !$exerciseOrderNumber) {
                $surahId = $request->query('surah_id');
                $verseNumber = $request->query('verse_number');

                if ($surahId && $verseNumber) {
                    $verse = Verse::with(['surah', 'wordGroups.words'])
                        ->where('surah_id', $surahId)
                        ->where('number', $verseNumber)
                        ->first();
                    if (!$verse) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Verse not found',
                        ], 404);
                    }
                    $resolvedExerciseOrderNumber = $verse->id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'verseId or (surah_id and verse_number) required',
                    ], 400);
                }
            }

            $hasWords = $verse->wordGroups->contains(function ($wordGroup) {
                return $wordGroup->words->isNotEmpty();
            });

            if (! $hasWords) {
                return response()->json([
                    'success' => false,
                    'message' => 'Words unavailable',
                ], 422);
            }

            $exercise = Exercise::findOrCreateQuranExercise($resolvedExerciseOrderNumber, $levelNumber);

            if (! $exercise->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exercise not found',
                ], 422);
            }

            $exerciseLevel = $exercise->exerciseLevel;

            $exercise->load('verse');
            $exercise->content = [
                'surah' => $verse->surah ? $verse->surah->only([
                    'id',
                    'name',
                    'name_id',
                    'name_en',
                    'location',
                    'verse_count'
                ]) : null,
                'verse' => $verse->only([
                    'id',
                    'surah_id',
                    'number',
                    'text',
                    'translation_indo'
                ]),
                'wordGroups' => $verse->wordGroups->map(function ($group) {
                    $groupData = $group->only([
                        'id',
                        'surah_id',
                        'verse_number',
                        'verse_id',
                        'order_number',
                        'text',
                        'created_at',
                        'updated_at',
                        'editor'
                    ]);
                    $groupData['words'] = $group->words->map(function ($word) {
                        return $word->only([
                            'id',
                            'word_group_id',
                            'order_number',
                            'text',
                            'translation',
                            'kalimat',
                            'color',
                            'kategori',
                            'hukum',
                            'kedudukan',
                            'irob',
                            'tanda',
                            'simbol',
                            'created_at',
                            'updated_at',
                            'editor'
                        ]);
                    })->toArray();
                    return $groupData;
                })->toArray(),
            ];

            if (auth()->check()) {
                $ua = UserAnswer::where('user_id', auth()->id())
                    ->where('exercise_id', $exercise->id)
                    ->where('passed', true)
                    ->latest()
                    ->first();

                $exercise->setAttribute('passed', $ua ? (bool) $ua->passed : false);
            }

            return response()->json([
                'success' => true,
                'message' => 'OK',
                'data' => $this->formatExerciseResponse($exercise),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format Exercise Response
     */
    protected function formatExerciseResponse(Exercise $exercise): array
    {
        if ($exercise->verse_id && ! $exercise->relationLoaded('verse')) {
            $exercise->load('verse');
        }

        if (! $exercise->relationLoaded('exerciseLevel')) {
            $exercise->load('exerciseLevel');
        }

        $response = $exercise->toArray();

        if (! empty($exercise->content) && is_array($exercise->content)) {
            $response['content'] = $exercise->content;
        }

        return $response;
    }

    /**
     * Activate Exercise.
     */
    public function activate(Request $request, $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->is_active = true;
        $exercise->save();

        return redirect()->back()->with('success', '"' . $exercise['title'] . '" succesfully activated');
    }

    /**
     * Deactivate Exercise
     */
    public function deactivate(Request $request, $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exercise->is_active = false;
        $exercise->save();

        return redirect()->back()->with('success', '"' . $exercise['title'] . '" succesfully deactivated');
    }

    /**
     * Grouping Words
     */
    public function grouping(string $id)
    {
        $exercise = Exercise::findOrFail($id);

        $wordGroups = $exercise->content['wordGroups'] ?? null;
        if (!$wordGroups) {
            $splitWords = preg_split('/\s+/', trim($exercise->description));
            $wordGroups = collect($splitWords)->map(function ($wordGroup, $index) {
                return [
                    'id' => $index + 1,
                    'text' => $wordGroup,
                ];
            });

            $content = $exercise->content;
            $content['wordGroups'] = $wordGroups;
            $exercise->content = $content;
            // $exercise->save();
        }

        $type_menu = 'admin.exercises';
        return view('pages.admin.exercise.grouping', compact('exercise', 'type_menu'));
    }

    /**
     * Update content->wordGroup
    */
    public function updateGrouping(Request $request, string $id)
    {
        $validated = $request->validate([
            'groups' => ['required', 'array'],
            'groups.*.text' => ['required', 'string'],
        ]);

        $exercise = Exercise::findOrFail($id);
        $content = $exercise->content ?? [];
        $content['wordGroups'] = collect($validated['groups'])
            ->values()
            ->map(function (array $group, int $index) {
                return [
                    'id' => $group['id'] ?? $index + 1,
                    'text' => $group['text'],
                    'order_number' => $index + 1,
                ];
            })
            ->all();

        $exercise->update(['content' => $content]);

        return response()->json([
            'success' => true,
            'message' => 'Soal latihan berhasil diperbarui.',
        ]);
    }
}
