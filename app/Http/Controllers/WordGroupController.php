<?php

namespace App\Http\Controllers;

use App\Models\WordGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordGroupController extends Controller
{
    /**
     * Display a listing of the Word Group.
     */
    public function index(Request $request)
    {
        $wordgroups = DB::table('word_groups')
            ->when($request->input('surah_id'), function ($query, $surah_id) {
                return $query->where('surah_id', '=', $surah_id);
            })
            ->orderBy('id', 'asc')
            ->paginate(50);

        return view('pages.wordgroups.index', compact('wordgroups'));
    }

    /**
     * Display a listing of the Word Group by Verse.
     */
    public function indexByVerse(Request $request)
    {
        $surahs = DB::table('surahs')->select('id', 'name', 'verse_count')->get();

        $wordgroups = DB::table('word_groups')
            ->where('surah_id', '=', $request->input('surah_id', 1)) // default 1
            ->where('verse_number', '=', $request->input('verse_number', 1)) // default 1         
            ->orderBy('id', 'asc')
            ->paginate(100);

        return view('pages.wordgroups.grouping', compact('surahs', 'wordgroups'));
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
     * Merge any word group in storage.
     */
    public function merge(Request $request)
    {
        // $ids = $request->input('ids');
        $ids = explode(',', $request->input('ids'));

        // Validate input
        if (! is_array($ids) || count($ids) < 2) {
            return redirect()->back()->with('error', 'Pilih minimal 2 baris untuk merge.');
        }

        // Sorting ID
        sort($ids);

        // Get word group by ID
        $wordGroups = WordGroups::whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        // Make sure all IDs are found
        if ($wordGroups->count() !== count($ids)) {
            return redirect()->back()->with('error', 'Beberapa ID tidak ditemukan.');
        }

        // Get data reference (surah & verse)
        $first = $wordGroups->first();

        // Validate: all surah & verse must in same verse
        $sameSurah = $wordGroups->every(fn ($wg) => $wg->surah_id === $first->surah_id);
        $sameVerse = $wordGroups->every(fn ($wg) => $wg->verse_number === $first->verse_number);

        if (! $sameSurah || ! $sameVerse) {
            return redirect()->back()->with('error', 'Semua baris harus dari surah & ayat yang sama.');
        }

        // Merge text by ID sorting
        $mergedText = $wordGroups
            ->pluck('text')
            ->map(fn ($t) => trim(str_replace(["\r", "\n"], '', $t)))
            ->implode(' ');

        // Run database transaction
        DB::transaction(function () use ($first, $ids, $mergedText) {
            // Update first row
            WordGroups::where('id', $first->id)->update([
                'text' => $mergedText,
            ]);

            // delete another row
            WordGroups::whereIn('id', array_slice($ids, 1))->delete();
        });

        $message = "$mergedText berhasil di gabungkan";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
