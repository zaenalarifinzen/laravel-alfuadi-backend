@extends('layouts.app')

@section('title', 'Pengaturan Analisa')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengaturan Analisa</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Batasi daftar soal dan ayat untuk analisa</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('admin.analysis-settings.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="allowed_surah_ids">Surah yang diizinkan</label>
                                <input type="text" class="form-control" id="allowed_surah_ids" name="allowed_surah_ids"
                                    value="{{ old('allowed_surah_ids', is_array($allowedSurahIds) ? implode($allowedSurahIds, ',') : $allowedSurahIds) }}"
                                    placeholder="Contoh: 1,2,3">
                                <small class="form-text text-muted">Pisahkan ID surah dengan koma.</small>
                            </div>

                            <div class="form-group">
                                <label for="allowed_verses_by_surah">Ayat yang diizinkan per surah</label>
                                @php
                                    $defaultAllowedVersesText = '';
                                    $oldValue = old('allowed_verses_by_surah');

                                    if ($oldValue !== null) {
                                        $defaultAllowedVersesText = $oldValue;
                                    } else {
                                        $raw = $allowedVersesBySurah ?? [];

                                        // If settings stored as JSON string, decode it
                                        if (is_string($raw)) {
                                            $decoded = json_decode($raw, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $raw = $decoded;
                                            }
                                        }

                                        $lines = [];
                                        if (is_array($raw)) {
                                            foreach ($raw as $surahId => $verses) {
                                                if (is_array($verses)) {
                                                    $lines[] = $surahId . ':' . implode(',', $verses);
                                                } elseif (is_string($verses) || is_numeric($verses)) {
                                                    $lines[] = $surahId . ':' . (string) $verses;
                                                }
                                            }
                                        }

                                        $defaultAllowedVersesText = implode("\n", $lines);
                                    }
                                @endphp

                                <textarea class="form-control" id="allowed_verses_by_surah" name="allowed_verses_by_surah" rows="8"
                                    placeholder="Contoh: 1:1,2,3">{{ $defaultAllowedVersesText }}</textarea>
                                <small class="form-text text-muted">Format: <strong>surah_id:ayat1,ayat2,ayat3</strong> per
                                    baris.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
