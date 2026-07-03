<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKedudukanRequest;
use App\Http\Requests\UpdateKedudukanRequest;
use App\Models\Kedudukan;
use Illuminate\Http\Request;

class KedudukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kedudukans = Kedudukan::orderBy('order', 'asc')
            ->get();
        
        return view('pages.skema-nahwu.kedudukan.index', compact('kedudukans'), ['type_menu' => '']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.skema-nahwu.kedudukan.create', ['type_menu' => '']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKedudukanRequest $request)
    {
        $data = $request->all();
        Kedudukan::create($data);
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $data['kedudukan_in'] . '" succesfully created')
            ->with('activeTab', 'kedudukan');
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
        $kedudukan = Kedudukan::findOrFail($id);
        return view('pages.skema-nahwu.kedudukan.edit', compact('kedudukan'), ['type_menu' => '']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKedudukanRequest $request, Kedudukan $kedudukan)
    {
        $data = $request->validated();
        $kedudukan->update($data);
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $kedudukan['kedudukan_in'] . '" succesfully updated')->with('activeTab', 'kedudukan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kedudukan $kedudukan)
    {
        $kedudukan->delete();
        return redirect()->route('skema-nahwu.index')
            ->with('success', '"' . $kedudukan['kedudukan_in'] . '" succesfully deleted')
            ->with('activeTab', 'kedudukan');
    }
}
