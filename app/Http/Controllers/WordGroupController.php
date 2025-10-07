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
        $ids = $request->input('ids');

        // Validasi awal
        if (! is_array($ids) || count($ids) < 2) {
            return view('pages.wordgroups.index', compact('wordgroups'));
        }

        // Urutkan dulu ID-nya untuk memastikan berurutan
        sort($ids);
        
        // Cek apakah urut tanpa lompat
        for ($i=1; $i < count($ids); $i++) { 
            if ($ids[$i] !== $ids[$i - 1] + 1) {
                return response()->toJson([
                    'message' => 'ID harus berurutan tanpa lompat'
                ], 422);
            }
        }

        // Ambil data word group berdasarkan ID
        $wordgroups = WordGroups::whereIn('id', $ids)
            ->orderBy('id')
            -get();

        // Pastikan semua ID ditemukan
        if ($wordgroups->count() !== count($ids)) {
            return response()->toJson(['message' => 'Beberapa ID tidak ditemukan'], 404);
        }

        // Ambil data referensi (surah & verse)
        $first = $wordgroups->first();

        // Harus dalam surah dan verse yang sama
        $sameSurah = $wordgroups->every(fn($wg) => $wg->surah_id === $first->surah_id);
        $sameVerse = $wordgroups->every(fn($wg) => $wg->verse_id === $first->verse_id);

        if (!$sameSurah || !$sameVerse) {
            return response()->toJson([
                'message' => 'Harus dalam surah dan ayat yang sama'
            ], 422);
        }

        // Gabungkan teks (berdasarkan urutan ID)
        $mergedText = $wordgroups->pluck('text')->implode(' ');

        // Jalankan transaksi database
        DB::transaction(function () use ($first, $ids, $mergedText) {
            // update baris pertama
            WordGroups::where('id', $first->id)->update([
                'text' => $mergedText,
            ]);

            // hapus baris lainnya
            WordGroup::whereIn('id', array_slice($ids, 1))->delete();
        });

        return response()->toJson([
            'message' => 'Merge Success',
            'id' => $first->id,
            'merged_text' => $mergedText,
            'surah_id' => $first->surah_id,
            'verse_id' => $first->verse_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
