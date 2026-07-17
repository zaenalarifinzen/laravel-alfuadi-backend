<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AnalysisSettingController extends Controller
{
    public function index()
    {
        return view('pages.admin.analysis-settings', [
            'type_menu' => 'admin',
            'allowedSurahIds' => Setting::getValue('analysis_allowed_surah_ids', ''),
            'allowedVersesBySurah' => Setting::getValue('analysis_allowed_verses_by_surah', []),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'allowed_surah_ids' => ['nullable', 'string'],
            'allowed_verses_by_surah' => ['nullable', 'string'],
        ]);

        Setting::setValue('analysis_allowed_surah_ids', $request->input('allowed_surah_ids', ''));

        $rawVerses = $request->input('allowed_verses_by_surah', '');
        $parsedVerses = [];

        if (!empty(trim((string) $rawVerses))) {
            $lines = preg_split('/\r\n|\n|\r/', trim((string) $rawVerses));
            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }

                [$surahId, $verses] = array_pad(explode(':', $line, 2), 2, '');
                $surahId = trim($surahId);
                $verses = trim($verses);

                if ($surahId === '' || $verses === '') {
                    continue;
                }

                $parsedVerses[$surahId] = array_values(array_filter(array_map(function ($value) {
                    $number = (int) trim((string) $value);
                    return $number > 0 ? $number : null;
                }, preg_split('/\s*,\s*/', $verses, -1, PREG_SPLIT_NO_EMPTY))));
            }
        }

        Setting::setValue('analysis_allowed_verses_by_surah', $parsedVerses);

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
