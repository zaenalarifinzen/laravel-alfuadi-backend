<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Models\Word;
use App\Models\WordGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Illuminate\Log\log;

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
            ->with([
                'editorInfo:id,name',
                'words' => function ($query) {
                    $query->orderBy('order_number', 'asc');
                },
            ])
            ->orderBy('order_number', 'asc')
            ->get();
        $first = $wordgroups->first();

        $surahId = $first->surah_id ?? null;
        $surahName = null;
        if ($first) {
            $surahName = DB::table('surahs')->where('id', $first->surah_id)->value('name');
        }
        $verseNumber = $first->verse_number ?? null;
        
        log($first);

        return view('pages.words.create', compact(
            'surahs',
            'surahId',
            'surahName',
            'verseNumber',
            'verseId',
            'wordgroups'
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
     * Sync words data from local to storage.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'groups' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->groups as $group) {
                    $words = $group['words'] ?? [];

                    // get all word id from request
                    $incomingWordIds = collect($words)->pluck('id')->filter()->toArray();

                    Word::where('word_group_id', $group['id'])
                        ->whereNotIn('id', $incomingWordIds)
                        ->delete();

                    // loop to insert or update
                    foreach ($words as $word) {
                        Word::updateOrCreate(
                            [
                                'id' => $word['id'] ?? null,
                            ],
                            [
                                'word_group_id' => $group['id'],
                                'order_number' => $word['order_number'] ?? 1,
                                'text' => $word['text'],
                                'translation' => $word['translation'] ?? null,
                                'kalimat' => $word['kalimat'] ?? null,
                                'jenis' => $word['jenis'] ?? null,
                                'hukum' => $word['hukum'] ?? null,
                                'mabni_detail' => $word['mabni_detail'] ?? null,
                                'category' => $word['category'] ?? null,
                                'kedudukan' => $word['kedudukan'] ?? null,
                                'irab' => $word['irab'] ?? null,
                                'alamat' => $word['alamat'] ?? null,
                                'condition' => $word['condition'] ?? null,
                                'matbu' => $word['matbu'] ?? null,
                            ]
                        );
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
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
