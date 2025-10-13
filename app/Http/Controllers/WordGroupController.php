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
            ->orderBy('order_number', 'asc')
            ->paginate(100);

        return view('pages.wordgroups.grouping', compact('surahs', 'wordgroups'));
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
     * Complete the order number of word groups in verse.
     */
    public function completeOrderNumber(Request $request)
    {
        $surahId = $request->input('surah_id');
        $verseNumber = $request->input('verse_number');

        if (! $surahId || ! $verseNumber) {
            return redirect()->back()->with('error', 'Surah dan ayat harus diisi.');
        }

        // Ambil semua word group dalam ayat ini
        $groups = WordGroups::where('surah_id', $surahId)
            ->where('verse_number', $verseNumber)
            ->orderBy('order_number', 'asc')
            ->get();

        if ($groups->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data di ayat ini');
        }

        DB::transaction(function () use ($groups) {
            foreach ($groups as $index => $wg) {
                $wg->update([
                    'order_number' => $index + 1,
                    'updated_at' => now(),
                ]);
            }
        });

        // Cari verse berikutnya
        $nextVerse = $verseNumber + 1;
        $maxVerse = DB::table('surahs')->where('id', $surahId)->value('verse_count');
        if ($nextVerse > $maxVerse) {
            // Jika sudah ayat terakhir, tetap di ayat terakhir
            $nextVerse = $maxVerse;
        }

        // Redirect ke ayat berikutnya
        return redirect()->route('wordgroups.indexByVerse', [
            'surah_id' => $surahId,
            'verse_number' => $nextVerse,
        ])->with('success', 'Update berhasil');
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
     * Split the specified resource from storage.
     */
    public function split(Request $request)
    {
        $id = $request->input('id');
        $delimiter = $request->input('delimiter', ' '); // default: spasi

        if (! $id) {
            return redirect()->back()->with('error', 'ID word group tidak boleh kosong.');
        }

        // Ambil data target
        $wordGroup = WordGroups::find($id);
        if (! $wordGroup) {
            return redirect()->back()->with('error', 'Word group tidak ditemukan.');
        }

        // Pecah teks berdasarkan spasi (atau delimiter custom)
        $parts = preg_split('/\s+/', trim($wordGroup->text));
        if (count($parts) < 2) {
            return redirect()->back()->with('error', 'Teks tidak bisa dipecah karena hanya satu kata.');
        }

        DB::transaction(function () use ($wordGroup, $parts) {
            // Ambil semua baris dalam ayat yang sama
            $groupsInVerse = WordGroups::where('surah_id', $wordGroup->surah_id)
                ->where('verse_number', $wordGroup->verse_number)
                ->orderBy('id', 'asc')
                ->get();

            // Inisialisasi order_number jika masih kosong
            $needsInit = $groupsInVerse->contains(fn ($wg) => is_null($wg->order_number));
            if ($needsInit) {
                foreach ($groupsInVerse as $index => $wg) {
                    $wg->update(['order_number' => $index + 1]);
                }
            }

            // Refresh data setelah update order_number
            $groupsInVerse = WordGroups::where('surah_id', $wordGroup->surah_id)
                ->where('verse_number', $wordGroup->verse_number)
                ->orderBy('order_number', 'asc')
                ->get();

            // Cari posisi baris yang akan dipecah
            $currentIndex = $groupsInVerse->search(fn ($wg) => $wg->id === $wordGroup->id);

            // Hapus baris lama
            $wordGroup->delete();

            // Geser order_number semua baris setelah posisi ini
            $afterGroups = $groupsInVerse->slice($currentIndex + 1);
            foreach ($afterGroups as $g) {
                $g->update([
                    'order_number' => $g->order_number + count($parts) - 1,
                ]);
            }

            // Sisipkan potongan baru
            foreach ($parts as $offset => $textPart) {
                WordGroups::create([
                    'surah_id' => $wordGroup->surah_id,
                    'verse_number' => $wordGroup->verse_number,
                    'order_number' => $currentIndex + $offset + 1,
                    'text' => trim($textPart),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Teks berhasil dipecah menjadi '.count($parts).' bagian dan urutan diperbarui.');
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
}
