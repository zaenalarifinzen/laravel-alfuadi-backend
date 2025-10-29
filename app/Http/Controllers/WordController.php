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
    public function index(Request $request)
    {
        $words = DB::table('words')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%'.$name.'%');
            })
            ->orderBy('id', 'asc')
            ->paginate(50);

        return view('pages.words.index', compact('words'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $surahs = Surah::select('id', 'name', 'verse_count')->get();
        $verseId = $request->input('verse_id', 1);
        $wordGroupId = $request->input('wordgroup_id', 1);

        $wordgroups = WordGroups::where('verse_id', $verseId)
            ->orderBy('order_number', 'asc')
            ->get();

        $words = Word::where('word_group_id', $wordGroupId)
            ->orderBy('order_number', 'asc')
            ->get();

        return view('pages.words.create', compact('surahs', 'wordgroups', 'words'));
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
