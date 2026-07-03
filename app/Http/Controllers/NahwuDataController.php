<?php

namespace App\Http\Controllers;

use App\Models\Kalimat;
use App\Models\Kategori;
use App\Models\Kedudukan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NahwuDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kalimats = Kalimat::orderBy('id', 'asc')->get();
        $kategoris = Kategori::orderBy('id', 'asc')->get();
        $kedudukans = Kedudukan::orderBy('id', 'asc')->get();
        $type_menu = 'skema-nahwu';

        return view('pages.skema-nahwu.index', compact('kalimats', 'kategoris', 'kedudukans', 'type_menu'));
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

    /**
     * CUSTOM FUNCTION
     */
    public function getAll() : JsonResponse {
        $data = Cache::remember('data-nahwu', now()->addHours(24), function () {
            $kalimat = Kalimat::orderBy('id')->get()->map(fn($k) => [
                'id' => (string) $k->id,
                'kalimat_ar' => $k->kalimat_ar,
                'kalimat_ar_musyakal' => $k->kalimat_ar_musyakal,
                'kalimat_in' => $k->kalimat_in,
            ]);

            $kategori = Kategori::orderBy('order')->get()->map(fn($k) => [
                'id' => $k->id,
                'id_kalimat' => (string) $k->id_kalimat,
                'order' => $k->order,
                'simbol' => $k->simbol === 'EMPTY' ? null : $k->simbol,
                'kategori_ar' => $k->kategori_ar,
                'kategori_ar_musyakal' => $k->kategori_ar_musyakal,
                'kategori_in' => $k->kategori_in,
                'hukum' => $k->hukum,
                'rofa' => $k->rofa,
                'nashob' => $k->nashob,
                'jar' => $k->jar,
                'jazm' => $k->jazm === 'EMPTY' ? null : $k->jazm,
            ]);

            $kedudukan = Kedudukan::orderBy('order')->get()->map(fn($k) => [
                'id' => $k->id,
                'id_kalimat' => (string) $k->id_kalimat,
                'order' => $k->order,
                'simbol' => $k->simbol,
                'kedudukan_ar' => $k->kedudukan_ar,
                'kedudukan_ar_musyakal' => $k->kedudukan_ar_musyakal,
                'kedudukan_in' => $k->kedudukan_in,
                'irob' => $k->irob,
            ]);

            return compact('kalimat', 'kategori', 'kedudukan');
        });

        return response()->json($data);
    }
}