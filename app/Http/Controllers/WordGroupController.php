<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveWordGroupsRequest;
use App\Models\Surah;
use App\Models\Verse;
use App\Models\Word;
use App\Models\WordGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Illuminate\Log\log;

class WordGroupController extends Controller
{
    /**
     * Display a listing of the Word Group.
     */
    public function index()
    {
        $wordgroups = WordGroups::orderBy('verse_id', 'asc')
            ->orderBy('order_number', 'asc')
            ->paginate(50);

        return view('pages.wordgroups.index', compact('wordgroups'), ['type_menu' => 'Al-Fuadi Database']);
    }

    /**
     * Display a listing of the Word Group.
     */
    public function getWordGroup(Request $request, $verse_id = null)
    {
        if ($verse_id) {
            $currentVerse = Verse::find($verse_id);
        } elseif ($request->filled('surah_id') && $request->filled('verse_number')) {
            $currentVerse = Verse::where('surah_id', $request->surah_id)
                ->where('number', $request->verse_number)
                ->first();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Parameter diperlukan',
            ], 400);
        }

        if (! $currentVerse) {
            return response()->json([
                'success' => false,
                'message' => 'Ayat tidak ditemukan',
            ], 404);
        }

        $currentSurah = Surah::find($currentVerse->surah_id);

        $existing = WordGroups::where('verse_id', $currentVerse->id)
            ->with([
                'editorInfo:id,name',
                'words' => function ($query) {
                    $query->with(['editorInfo:id,name'])->orderBy('order_number', 'asc');
                },
            ])
            ->orderBy('order_number', 'asc')
            ->get();

        if ($existing->isNotEmpty()) {
            $wordGroups = $existing;
            $isPersisted = true;
        } else {
            $splitWords = preg_split('/\s+/', trim($currentVerse->text));
            $wordGroups = collect($splitWords)->map(function ($wordGroup, $index) use ($currentVerse) {
                return (object) [
                    'id' => $index + 1,
                    'surah_id' => $currentVerse->surah_id,
                    'verse_id' => $currentVerse->id,
                    'verse_number' => $currentVerse->number,
                    'text' => $wordGroup,
                    'editorInfo' => null,
                ];
            });
            $isPersisted = false;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'surah' => $currentSurah,
                'verse' => $currentVerse,
                'wordGroups' => $wordGroups,
                'isPersisted' => $isPersisted,
            ],
        ]);
    }

    /**
     * Display a listing of the Word Group by Verse.
     */
    public function grouping(Request $request)
    {
        $surahs = DB::table('surahs')->select('id', 'name', 'verse_count')->get();

        // ambil data dari tabel verses
        $currentVerse = null;
        if ($request->filled('verse_id')) {
            $currentVerse = Verse::where('id', $request->verse_id)->first();
        } elseif ($request->filled('surah_id') && $request->filled('verse_number')) {
            $currentVerse = Verse::where('surah_id', $request->surah_id)
                ->where('number', $request->verse_number)->first();
        } else {
            $currentVerse = Verse::where('id', 1)->first();
        }

        if (! $currentVerse) {
            return redirect()->back()->with('error', 'Ayat tidak ditemukan');
        }

        $currentSurah = $currentSurah = Surah::where('id', $currentVerse->surah_id)->first();

        $existing = WordGroups::where('verse_id', $currentVerse->id)
            ->with(['editorInfo:id,name'])
            ->orderBy('order_number', 'asc')
            ->get();

        if ($existing->isNotEmpty()) {
            // ambil dari data yang sudah ada
            $words = $existing;
            $isPersisted = true;
        } else {
            // split dari verse berdasarkan spasi
            $splitWords = preg_split('/\s+/', trim($currentVerse->text));
            $words = collect($splitWords)->map(function ($word, $index) use ($currentVerse) {
                return (object) [
                    'id' => $index + 1,
                    'surah_id' => $currentVerse->surah_id,
                    'verse_id' => $currentVerse->id,
                    'verse_number' => $currentVerse->number,
                    'text' => $word,
                    'editorInfo' => null,
                ];
            });
            $isPersisted = false;
        }

        $data = [
            'surahs' => $surahs,
            'currentSurah' => $currentSurah,
            'currentVerse' => $currentVerse,
            'words' => $words,
            'isPersisted' => $isPersisted,
        ];

        return view('pages.wordgroups.grouping', $data, ['type_menu' => 'Tools']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'surah_id' => 'required|integer',
            'verse_number' => 'required|integer',
            'text' => 'required|string',
        ]);

        // create word group
        WordGroups::create($request->only('surah_id', 'verse_number', 'text'));

        return redirect()->back()->with('success', 'Word group berhasil ditambahkan.');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'surah_id' => 'required|integer',
            'verse_number' => 'required|integer',
            'groups' => 'required|array',
            'groups.*.text' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $verse = Verse::where('surah_id', $validated['surah_id'])
                    ->where('number', $validated['verse_number'])
                    ->first();

                if (! $verse) {
                    throw new \Exception('Verse tidak ditemukan.');
                }

                // hapus dulu word group lama dari ayat ini
                WordGroups::where('verse_id', $verse->id)->delete();

                foreach ($validated['groups'] as $order => $group) {
                    WordGroups::create([
                        'surah_id' => $validated['surah_id'],
                        'verse_number' => $validated['verse_number'],
                        'verse_id' => $verse->id,
                        'text' => $group['text'],
                        'order_number' => $order + 1,
                        'editor' => auth()->id(),
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Data tersimpan']);
        } catch (\Throwable $e) {
            \Log::error('WordGroup save error: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function multipleUpdate(Request $request)
    {
        $data = $request->all();

        DB::transaction(function () use ($data) {
            $verseId = $data['verse_id'] ?? null;
            $mergedMap = $data['merged_map'] ?? [];
            $editedGroups = $data['edited_groups'] ?? [];
            $deletedIds = $data['deleted_ids'] ?? [];

            $verse = Verse::findOrFail($verseId);

            // update foreign key in words
            foreach ($mergedMap as $oldId => $newId) {
                $validNew = WordGroups::where('id', $newId)
                    ->where('verse_id', $verseId)
                    ->exists();

                if (! $validNew) {
                    throw new Exception("Invalid merge target ID: $newId for verse $verseId");
                }

                Word::where('word_group_id', $oldId)
                    ->update(['word_group_id' => $newId]);
            }

            // create or edit wordgroups
            foreach ($editedGroups as $i => $eg) {
                WordGroups::updateOrCreate(
                    [
                        'id' => $eg['id'] ?? null,
                    ],
                    [
                        'surah_id' => $data['surah_id'],
                        'verse_number' => $data['verse_number'],
                        'verse_id' => $verse->id,
                        'text' => $eg['text'] ?? '',
                        'order_number' => $eg['order_number'] ?? ($i + 1),
                        'editor' => auth()->id(),
                    ]);
            }

            // delete wordgroups from deletedIds
            if (!empty($deletedIds)) {
                WordGroups::whereIn('id', $deletedIds)
                    ->where('verse_id', $verseId)
                    ->delete();
            }
        });

        return response()->json(['success' => true]);
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
        // remove word group by id
        $wordGroup = WordGroups::find($id);
        if (! $wordGroup) {
            return redirect()->back()->with('error', 'Word group tidak ditemukan.');
        }

        // Run database transaction
        DB::transaction(function () use ($wordGroup) {
            $wordGroup->delete();
        });

        return redirect()->back()->with('success', 'Word group berhasil dihapus.');
    }

    /**
     * TEST FUNCTION
     */
    public function testSave(Request $request)
    {
        // Cek data yang dikirim dari frontend
        $data = $request->all();

        // Log untuk memastikan diterima dengan benar
        \Log::info('Test Save Request:', $data);

        // Balikkan response JSON untuk verifikasi
        return response()->json([
            'message' => 'Test data received successfully!',
            'received' => $data,
        ]);
    }
}
