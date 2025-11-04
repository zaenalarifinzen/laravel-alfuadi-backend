<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Models\Word;
use App\Models\WordGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $words = Word::orderBy('word_group_id', 'asc')
            ->orderBy('order_number', 'asc')
            ->paginate(25);

        return view('pages.words.index', compact('words'), ['type_menu' => 'Al-Fuadi Database']);
    }

    /**
     * Get a listing of the words.
     */
    public function getWord($word_group_id)
    {
        // Ambil semua word berdasarkan word_group_id
        $words = Word::where('word_group_id', $word_group_id)
            ->orderBy('order_number', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $words,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $surahs = Surah::select('id', 'name', 'verse_count')->get();
        $verseId = $request->input('verse_id', 1);
        $wordGroupId = $request->input('word_group_id', 1);

        $wordgroups = WordGroups::where('verse_id', $verseId)
            ->orderBy('order_number', 'asc')
            ->get();
        $first = $wordgroups->first();

        $words = Word::where('word_group_id', $wordGroupId)
            ->orderBy('order_number', 'asc')
            ->get();

        $surahId = $first->surah_id ?? null;
        $surahName = null;
        if ($first) {
            $surahName = DB::table('surahs')->where('id', $first->surah_id)->value('name');
        }
        $verseNumber = $first->verse_number ?? null;

        return view('pages.words.create', compact(
            'surahs',
            'surahId',
            'surahName',
            'verseNumber',
            'verseId',
            'wordgroups',
            'words',
        ),
            ['type_menu' => 'Tools']);
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
}
