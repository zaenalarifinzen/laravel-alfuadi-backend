<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Kalimat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('order', 'asc')
            ->get();
        
        return view('pages.skema-nahwu.kategori.index', compact('kategoris'), ['type_menu' => '']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kalimats = Kalimat::orderBy('id', 'asc')->get();
        return view('pages.skema-nahwu.kategori.create', compact('kalimats'), ['type_menu' => '']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request)
    {
        $data = $request->validated();
        $data['order'] = (Kategori::max('order') ?? 0) + 1;

        Kategori::create($data);
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $data['kategori_in'] . '" succesfully created')
            ->with('activeTab', 'kategori');
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
        $kategori = Kategori::findOrFail($id);
        $kalimats = Kalimat::orderBy('id', 'asc')->get();
        return view('pages.skema-nahwu.kategori.edit', compact('kategori', 'kalimats'), ['type_menu' => '']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $data = $request->validated();
        $kategori->update($data);
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $data['kategori_in'] . '" succesfully updated')
            ->with('activeTab', 'kategori');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $kategori['kategori_in'] . '" succesfully deleted')
            ->with('activeTab', 'kategori');
    }
}
