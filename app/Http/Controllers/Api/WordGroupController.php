<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WordGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordGroupController extends Controller
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
     * Merge any word group in storage.
     */
    public function merge(Request $request)
    {
        $ids = $request->input('ids');

        // 🔹 Validasi awal
        if (!is_array($ids) || count($ids) < 2) {
            return response()->json(['message' => 'Minimal 2 ID diperlukan untuk merge'], 400);
        }

        // 🔹 Urutkan dulu ID-nya
        sort($ids);

        // 🔹 Cek apakah urut tanpa lompat
        for ($i = 1; $i < count($ids); $i++) {
            if ($ids[$i] !== $ids[$i - 1] + 1) {
                return response()->json([
                    'message' => 'ID harus berurutan tanpa lompat (contoh: [1,2,3], bukan [1,3,5])'
                ], 422);
            }
        }

        // 🔹 Ambil data word group berdasarkan ID
        $wordGroups = WordGroups::whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        // 🔹 Pastikan semua ID ditemukan
        if ($wordGroups->count() !== count($ids)) {
            return response()->json(['message' => 'Beberapa ID tidak ditemukan'], 404);
        }

        // 🔹 Ambil data referensi (surah & verse)
        $first = $wordGroups->first();

        // 🔹 Validasi: semua harus dalam surah & verse yang sama
        $sameSurah = $wordGroups->every(fn($wg) => $wg->surah_id === $first->surah_id);
        $sameVerse = $wordGroups->every(fn($wg) => $wg->verse_number === $first->verse_number);

        if (!$sameSurah || !$sameVerse) {
            return response()->json([
                'message' => 'Semua baris harus memiliki surah_id dan verse_id yang sama'
            ], 422);
        }

        // 🔹 Gabungkan teks berdasarkan urutan ID
        $mergedText = $wordGroups->pluck('text')->implode(' ');

        // 🔹 Jalankan transaksi database
        DB::transaction(function () use ($first, $ids, $mergedText) {
            // Update baris pertama
            WordGroups::where('id', $first->id)->update([
                'text' => $mergedText,
            ]);

            // Hapus baris lainnya
            WordGroups::whereIn('id', array_slice($ids, 1))->delete();
        });

        return response()->json([
            'message' => 'Merge berhasil',
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
