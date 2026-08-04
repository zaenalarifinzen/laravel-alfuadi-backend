<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function index(Request $request)
    {
        $surahs = Surah::orderBy('id', 'asc')->get();

        return view('pages.quran.index', compact('surahs'), ['type_menu' => 'Al-Quran']);
    }

    public function versesOfSurah(Request $request)
    {
        $surah = Surah::where('id', $request->id)->first();
        $verses = $surah->verses;

        return view('pages.quran.surah', compact('surah', 'verses'), ['type_menu' => 'Al-Quran']);
    }
}
