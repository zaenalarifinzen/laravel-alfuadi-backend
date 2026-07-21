@extends('layouts.app')

@section('title', 'Pengaturan Analisa')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengaturan Analisa</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.analysis-settings.store') }}">
                    @csrf
                    <div class="row">
                        @foreach ($surahs as $surah)
                            @php
                                $raw = $allowedSurahConfig[$surah->id] ?? [];
                                $cfg = [
                                    'enabled' => $raw['enabled'] ?? false,
                                    'max_verse' => $raw['max_verse'] ?? $surah->verse_count,
                                ];
                            @endphp
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{ $surah->id }}. {{ $surah->name }}</h4>
                                        <div class="card-header-action">
                                            <label class="custom-switch mt-2">
                                                <input type="checkbox" name="allowed_surahs[{{ $surah->id }}][enabled]"
                                                    value="1" class="custom-switch-input"
                                                    {{ $cfg['enabled'] ? 'checked' : '' }}>
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <input type="number" name="allowed_surahs[{{ $surah->id }}][max_verse]" min=1
                                            max="{{ $surah->verse_count }}" class="form-control"
                                            value="{{ old('allowed_surahs.' . $surah->id . '.max_verse', $cfg['max_verse']) }}"
                                            placeholder="maksimal {{ $surah->verse_count }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </section>
    </div>
@endsection
