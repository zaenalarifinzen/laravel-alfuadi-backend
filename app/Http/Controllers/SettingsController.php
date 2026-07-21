<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Surah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surahs = Surah::select('id', 'name', 'verse_count')->orderBy('id')->get();
        $type_menu = 'admin';
        $allowedSurahConfig = Setting::getJson('analysis_allowed_surah_config', []);

        return view('pages.admin.analysis-settings.index', compact('surahs', 'type_menu', 'allowedSurahConfig'));
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
        // Normalize input: remove max_verse for items that are not enabled
        $allowed = $request->input('allowed_surahs', []);
        foreach ($allowed as $surahId => $item) {
            $enabled = isset($item['enabled']) && filter_var($item['enabled'], FILTER_VALIDATE_BOOLEAN);

            if (! $enabled) {
                // Remove max_verse so validation (min:1) won't reject zero/empty values
                unset($allowed[$surahId]['max_verse']);
            } else {
                // ensure numeric value present for enabled items
                if (! isset($allowed[$surahId]['max_verse'])) {
                    $surahModel = \App\Models\Surah::find($surahId); // sesuaikan nama model
                    $allowed[$surahId]['max_verse'] = $surahModel?->verse_count ?? 1;
                }
            }
        }

        // Merge normalized data back into the request for validation
        $request->merge(['allowed_surahs' => $allowed]);

        $request->validate([
            'allowed_surahs' => ['nullable', 'array'],
            'allowed_surahs.*.enabled' => ['nullable', 'boolean'],
            'allowed_surahs.*.max_verse' => ['nullable', 'integer', 'min:1'],
        ]);

        $config = [];
        foreach ($request->input('allowed_surahs', []) as $surahId => $item) {
            $enabled = isset($item['enabled']) && filter_var($item['enabled'], FILTER_VALIDATE_BOOLEAN);
            $maxVerse = $item['max_verse'] ?? null;

            if ($enabled && $maxVerse !== null && (int) $maxVerse > 0) {
                $config[$surahId] = [
                    'enabled' => true,
                    'max_verse' => (int) $maxVerse,
                ];
            } else {
                $config[$surahId] = [
                    'enabled' => false,
                    'max_verse' => $maxVerse !== null ? (int) $maxVerse : null,
                ];
            }
        }

        try {
            Setting::setValue('analysis_allowed_surah_config', $config);
        } catch (\Throwable $e) {
            Log::error('Failed to save analysis_allowed_surah_config', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.analysis-settings.index')->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
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
