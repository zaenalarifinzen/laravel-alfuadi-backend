<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
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
        return view('pages.skema-nahwu.kategori.create', ['type_menu' => '']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request)
    {
        $data = $request->all();

        Kategori::create($data);
        return redirect()->route('kategori.index')->with('success', '"' . $data['kategori_in'] . '" succesfully created');
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
        return view('pages.skema-nahwu.kategori.edit', compact('kategori'), ['type_menu' => '']);
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
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', '"' . $kategori['kategori_in'] . '" succesfully deleted');
    }
}
