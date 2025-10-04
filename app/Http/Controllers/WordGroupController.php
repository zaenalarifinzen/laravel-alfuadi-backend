<?php

namespace App\Http\Controllers;

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
                return $query->where('surah_number', '=', $surah_id);
            })
            ->orderBy('id', 'asc')
            ->paginate(50);

        return view('pages.wordgroups.index', compact('wordgroups'));
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
