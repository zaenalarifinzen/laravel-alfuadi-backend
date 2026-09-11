<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use App\Models\ExerciseLevel;
use App\Models\ExerciseSubmission;
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
            ->orderBy('level_id', 'asc')
            ->orderBy('display_order', 'asc')
            ->get();

        foreach ($exercises as $exercise) {
            $exercise->load('exerciseLevel');
        }

        $type_menu = 'dashboard.exercises.exercise';

        return view('pages.dashboard.exercise.index', compact('exercises', 'type_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = ExerciseLevel::orderBy('level_number', 'asc')->get();
        $type_menu = 'dashboard.exercises.exercise';

        return view('pages.dashboard.exercise.create', compact('levels', 'type_menu'));
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
            $maxDisplayOrder = Exercise::where('level_id', $data['level_id'])->max('display_order');
            $data['display_order'] = $maxDisplayOrder ? $maxDisplayOrder + 1 : 1;
        }

        Exercise::create($data);
        return redirect()
            ->route('dashboard.exercises.index')
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
        $type_menu = 'dashboard.exercises.exercise';

        return view('pages.dashboard.exercise.edit', compact('exercise', 'levels', 'type_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, string $id)
    {
        $data = $request->validated();

        $exercise = Exercise::findOrFail($id);
        $exerciseDescription = $exercise->description ?? [];
        $newDescription = $data['description'] ?? [];

        if ($exerciseDescription !== $newDescription) {
            $data['content'] = null;
            $data['is_active'] = false;
        }

        $exercise->update($data);

        return redirect()->route('dashboard.exercises.index')
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

    public function getExerciseList(Request $request, $level)
    {
        $exerciseLevel = ExerciseLevel::where('slug', $level)->active()->first();

        if (!$exerciseLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Level not found',
            ], 404);
        }

        if ($level === 'alquran') {
            $surahId = $request->query('surah_id');

            $query = Verse::query()->whereHas('wordGroups.words');

            if ($surahId) {
                $query->where('surah_id', $surahId);
            } else {
                $firstVerse = (clone $query)->first();
                if ($firstVerse) {
                    $query->where('surah_id', $firstVerse->surah_id);
                }
            }

            $verses = $query->orderBy('number', 'asc')->get(['id', 'surah_id', 'number', 'text']);

            if (auth()->check()) {
                $exerciseIds = Exercise::whereIn('verse_id', $verses->pluck('id'))->pluck('id', 'verse_id');
                $passedExerciseIds = ExerciseSubmission::where('user_id', auth()->id())
                    ->whereIn('exercise_id', $exerciseIds->values())
                    ->where('passed', true)
                    ->pluck('exercise_id')
                    ->toArray();

                foreach ($verses as $verse) {
                    $exId = $exerciseIds[$verse->id] ?? null;
                    $verse->passed = $exId ? in_array($exId, $passedExerciseIds) : false;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'level_id' => $exerciseLevel,
                    'exercises' => $verses->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'order_number' => $v->id,
                            'title' => 'Ayat ' . $v->number,
                            'subtitle' => $v->text,
                            'passed' => $v->passed ?? false,
                        ];
                    }),
                ]
            ]);
        }

        $exercises = Exercise::active()
            ->where('level_id', $exerciseLevel->level_number)
            ->orderBy('display_order', 'asc')
            ->get(['id', 'title', 'description', 'level_id', 'display_order', 'verse_id']);

        if (auth()->check()) {
            $passedExerciseIds = ExerciseSubmission::where('user_id', auth()->id())
                ->whereIn('exercise_id', $exercises->pluck('id'))
                ->where('passed', true)
                ->pluck('exercise_id')
                ->toArray();

            foreach ($exercises as $ex) {
                $ex->passed = in_array($ex->id, $passedExerciseIds);
            }
        }

        // add level info in 'data' response, jadi di data nanti ada exercises dan level info
        return response()->json([
            'success' => true,
            'data' => [
                'level' => $exerciseLevel,
                'exercises' => $exercises->map(function ($ex) {
                    return [
                        'id' => $ex->id,
                        'order_number' => $ex->display_order,
                        'title' => $ex->title ?: ('Soal ' . $ex->display_order),
                        'subtitle' => $ex->description ?? '',
                        'passed' => $ex->passed ?? false,
                    ];
                }),
            ],
        ]);
    }

    /**
     * CUSTOM FUNCTION
     */
    public function getExercise(Request $request, $level = null, $identifier = null)
    {
        if ($level === 'alquran') {
            return $this->getQuranExercise($request, $level, $identifier);
        } else {
            return $this->getBasicExercise($level, $identifier);
        }
    }

    private function getBasicExercise($level = null, $exerciseId = null)
    {
        $exerciseLevel = ExerciseLevel::where('slug', $level)->active()->first();
        $exerciseId = (int) $exerciseId ;

        if ($exerciseId) {
            $exercise = Exercise::active()
                ->where('level_id', $exerciseLevel->id)
                ->where('id', $exerciseId)
                ->first();
        } else {
            $exercise = Exercise::active()
                ->where('level_id', $exerciseLevel->id)
                ->where('display_order', 1)
                ->first();
        }

        if (!$exercise || !$exercise->content) {
            return response()->json([
                'success' => false,
                'message' => 'Exercise not found',
            ], 404);
        }

        if (auth()->check()) {
            $ua = ExerciseSubmission::where('user_id', auth()->id())
                ->where('exercise_id', $exercise->id)
                ->where('passed', true)
                ->latest()
                ->first();

            $exercise->setAttribute('passed', $ua ? (bool) $ua->passed : false);
        }

        // add attribute prev and next exercise id
        $prevExercise = Exercise::active()
            ->where('level_id', $exerciseLevel->id)
            ->where('display_order', '<', $exercise->display_order)
            ->orderBy('display_order', 'desc')
            ->first();

        $nextExercise = Exercise::active()
            ->where('level_id', $exerciseLevel->id)
            ->where('display_order', '>', $exercise->display_order)
            ->orderBy('display_order', 'asc')
            ->first();

        $exercise->setAttribute('prev_exercise_id', $prevExercise ? $prevExercise->id : null);
        $exercise->setAttribute('next_exercise_id', $nextExercise ? $nextExercise->id : null);

        // The first exercise is always available; later exercises require the previous one to be passed.
        $firstExercise = Exercise::active()
            ->where('level_id', $exerciseLevel->id)
            ->orderBy('display_order', 'asc')
            ->orderBy('id', 'asc')
            ->first();
        $isFirstExercise = $firstExercise && $firstExercise->id === $exercise->id;

        if (!$isFirstExercise) {
            if (!$prevExercise || !auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Previous exercise not passed',
                ], 403);
            }

            $prevExerciseSubmission = ExerciseSubmission::where('user_id', auth()->id())
                ->where('exercise_id', $prevExercise->id)
                ->where('passed', true)
                ->latest()
                ->first();

            if (!$prevExerciseSubmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Previous exercise not passed',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $this->formatExerciseResponse($exercise),
        ], 200);
    }

    private function getQuranExercise(Request $request, $level = null, $verseId = null)
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
            } elseif ($request->filled('level_id')) {
                $levelNumber = (int) $request->query('level_id');
            } elseif ($level) {
                $exerciseLevel = ExerciseLevel::where('slug', $level)->active()->first();
                $levelNumber = $exerciseLevel ? $exerciseLevel->level_number : (int) $level;
            }

            $verse = null;
            $resolvedExerciseId = $verseId;

            if ($exerciseLevel->slug === 'alquran' && $verseId) {
                $verse = Verse::with(['surah', 'wordGroups.words'])->find($verseId);
                if (!$verse) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Verse not found',
                    ], 404);
                }
                $resolvedExerciseId = $verse->id;
            } elseif ($exerciseLevel->slug === 'alquran' && !$verseId) {
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
                    $resolvedExerciseId = $verse->id;
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

            $exercise = Exercise::findOrCreateQuranExercise($resolvedExerciseId, $levelNumber);

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
                        'updated_at',
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
                            'updated_at',
                        ]);
                    })->toArray();
                    return $groupData;
                })->toArray(),
            ];

            if (auth()->check()) {
                $ua = ExerciseSubmission::where('user_id', auth()->id())
                    ->where('exercise_id', $exercise->id)
                    ->where('passed', true)
                    ->latest()
                    ->first();

                $exercise->setAttribute('passed', $ua ? (bool) $ua->passed : false);
            }

            $exerciseOrderNumber = $exercise->display_order;

            $prevExercise = Exercise::active()
                ->where('level_id', $levelNumber)
                ->where('display_order', '<', $exerciseOrderNumber)
                ->orderBy('display_order', 'desc')
                ->first();

            $nextExercise = Exercise::active()
                ->where('level_id', $levelNumber)
                ->where('display_order', '>', $exerciseOrderNumber)
                ->orderBy('display_order', 'asc')
                ->first();

            $exercise->setAttribute('prev_exercise_id', $prevExercise ? $prevExercise->id : null);
            $exercise->setAttribute('next_exercise_id', $nextExercise ? $nextExercise->id : null);
            $exercise->setAttribute('prev_verse_id', $prevExercise ? $prevExercise->verse_id : null);
            $exercise->setAttribute('next_verse_id', $nextExercise ? $nextExercise->verse_id : null);

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
    public function activate(string $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        $exerciseWordGroups = $exercise->content['wordGroups'] ?? null;
        $exerciseWords = collect($exerciseWordGroups)->flatMap(function ($group) {
            return $group['words'] ?? [];
        });

        if (!$exerciseWordGroups || $exerciseWords->isEmpty()) {
            return redirect()->back()->with('error', 'Cannot activate "' . $exercise['title'] . '" because it has no wordgroups or words.');
        }

        $exercise->is_active = true;
        $exercise->save();

        return redirect()->back()->with('success', '"' . $exercise['title'] . '" succesfully activated');
    }

    /**
     * Deactivate Exercise
     */
    public function deactivate(string $exerciseId)
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

        $type_menu = 'dashboard.exercises';
        return view('pages.dashboard.exercise.grouping', compact('exercise', 'type_menu'));
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

    /**
     * Input Irob
     */
    public function irob(string $id)
    {
        $exercise = Exercise::findOrFail($id);
        $type_menu = 'dashboard.exercises';

        return view('pages.dashboard.exercise.irob', compact('exercise', 'type_menu'));
    }

    public function updateIrob(Request $request, string $id)
    {
        $validated = $request->validate([
            'wordGroups' => ['required', 'array'],
        ]);

        $exercise = Exercise::findOrFail($id);
        $content = $exercise->content ?? [];
        $content['wordGroups'] = collect($validated['wordGroups'])
            ->values()
            ->map(function (array $group, int $index) {
                return [
                    'id' => $group['id'] ?? $index + 1,
                    'text' => $group['text'],
                    'order_number' => $index + 1,
                    'words' => collect($group['words'])
                        ->values()
                        ->map(function (array $word, int $wordIndex) {
                            return [
                                'id' => $word['id'] ?? null,
                                'word_group_id' => $word['word_group_id'] ?? null,
                                'order_number' => $wordIndex + 1,
                                'text' => $word['text'] ?? '',
                                'color' => $word['color'] ?? '',
                                'translation' => $word['translation'] ?? '',
                                'kalimat' => $word['kalimat'] ?? null,
                                'hukum' => $word['hukum'] ?? null,
                                'kategori' => $word['kategori'] ?? null,
                                'kedudukan' => $word['kedudukan'] ?? null,
                                'irob' => $word['irob'] ?? null,
                                'tanda' => $word['tanda'] ?? null,
                                'simbol' => $word['simbol'] ?? null,
                            ];
                        })
                        ->all(),
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
