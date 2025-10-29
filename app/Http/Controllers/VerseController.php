<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerseController extends Controller
{
    /**
     * Display verse by surah id and verse number.
     */
    public function index(Request $request)
    {
        // get verse
        $query = DB::table('verses')
            ->when($request->surah_id, fn($q) => $q->where('surah_id', $request->surah_id))
            ->when($request->verse_number, fn($q) => $q->where('number', $request->verse_number))
            ->orderBy('id', 'asc');

        $verses = $query->paginate(50);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($verses);
        }

        return view('pages.verses.index', compact('verses'));
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
}
