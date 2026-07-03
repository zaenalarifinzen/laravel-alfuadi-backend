<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKalimatRequest;
use App\Http\Requests\UpdateKalimatRequest;
use App\Models\Kalimat;
use Illuminate\Http\Request;

class KalimatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kalimats = Kalimat::orderBy('id', 'asc')
            ->get();

        return view('pages.skema-nahwu.kalimat.index', compact('kalimats'), ['type_menu' => 'Al-Fuadi Database']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.skema-nahwu.kalimat.create', ['type_menu' => '']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKalimatRequest $request)
    {
        $data = $request->all();

        Kalimat::create($data);
        return redirect()->route('kalimat.index')->with('success', '"' . $data['kalimat_in'] . '" succesfully created');
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
        $kalimat = Kalimat::findOrFail($id);
        return view('pages.skema-nahwu.kalimat.edit', compact('kalimat'), ['type_menu' => '']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKalimatRequest $request, Kalimat $kalimat)
    {
        $data = $request->validated();
        $kalimat->update($data);
        return redirect()->route('kalimat.index')->with('success', '"' . $data['kalimat_in'] . '" succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kalimat $kalimat)
    {
        $kalimat->delete();
        return redirect()->route('kalimat.index')->with('success', '"' . $kalimat['kalimat_in'] . '" succesfully deleted');
    }
}
