<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Verse;
use Illuminate\Http\Request;

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
    public function getAnalysisQuestion($verseId, $level = 1)
    {
        try {
            $verse = Verse::with(['surah', 'wordGroups.words'])->find($verseId);
            if (!$verse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ayat tidak ditemukan',
                ], 404);
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
