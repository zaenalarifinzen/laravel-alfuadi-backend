<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionLevel;
use App\Models\UserAnswer;
use App\Models\Verse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * CUSTOM FUNCTION
     */
    public function getAnalysisQuestion(Request $request, $verseId = null)
    {
        try {
            $level = 99;
            Log::info($level);

            if ($request->filled('level_slug')) {
                $questionLevel = QuestionLevel::where('slug', $request->query('level_slug'))->active()->first();
                Log::info($questionLevel);

                if (!$questionLevel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Level soal tidak ditemukan',
                    ], 404);
                }
                $level = $questionLevel->level_number;
            } elseif ($request->filled('level')) {
                $level = (int) $request->query('level');
            }

            // If verseId not provided, try to find verse by surah_id and verse_number
            if (!$verseId) {
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
                            'message' => 'Ayat tidak ditemukan',
                        ], 404);
                    }
                    $verseId = $verse->id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Parameter verseId atau (surah_id dan verse_number) diperlukan',
                    ], 400);
                }
            } else {
                $verse = Verse::with(['surah', 'wordGroups.words'])->find($verseId);
                if (!$verse) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ayat tidak ditemukan',
                    ], 404);
                }
            }

            $question = Question::findOrCreateAnalysisQuestion($verseId, $level);
            $question->load('verse');

            $question->content = [
                'surah' => $verse->surah ? $verse->surah->only([
                    'id',
                    'name',
                    'name_id',
                    'name_en',
                    'location',
                    'verse_count'
                ]) : null,
                'verse' => $verse->only([
                    'id', 'surah_id', 
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
                    ->where('question_id', $question->id)
                    ->where('passed', true)
                    ->latest()
                    ->first();

                $question['passed'] = $ua ? (bool) $ua->passed : false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Soal analisa ayat',
                'data' => $question,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
