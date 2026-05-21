@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
<style>
/* ====================================
   LAYOUT
==================================== */
.latihan-page {
    padding: 1.5rem;
}
 
/* ====================================
   RESUME CARD
==================================== */
.resume-card {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    background: #fff;
    border: 2px solid #1D9E75;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}
 
.resume-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #E1F5EE;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #0F6E56;
    font-size: 18px;
}
 
.resume-meta {
    flex: 1;
    min-width: 0;
}
 
.resume-label {
    font-size: 11px;
    font-weight: 600;
    color: #0F6E56;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 3px;
}
 
.resume-title {
    font-size: 15px;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 2px;
}
 
.resume-sub {
    font-size: 13px;
    color: #6b7280;
}
 
.resume-btn {
    background: #1D9E75;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1.1rem;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background 0.15s;
}
 
.resume-btn:hover {
    background: #0F6E56;
    color: #fff;
}
 
/* ====================================
   STATS
==================================== */
.stats-row {
    display: flex;
    gap: 12px;
    margin-bottom: 1.75rem;
}
 
.stat-card {
    flex: 1;
    background: #f9fafb;
    border-radius: 10px;
    padding: 0.85rem 1rem;
    text-align: center;
    border: 0.5px solid #e5e7eb;
}
 
.stat-num {
    font-size: 22px;
    font-weight: 600;
    color: #1a1a1a;
}
 
.stat-lbl {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
}
 
/* ====================================
   SECTION LABEL
==================================== */
.section-label {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 1rem;
}
 
/* ====================================
   ROADMAP
==================================== */
.levels-wrapper {
    display: flex;
    flex-direction: column;
}
 
.connector {
    display: flex;
    justify-content: center;
    padding: 2px 0;
}
 
.connector-line {
    width: 2px;
    height: 16px;
    background: #e5e7eb;
    border-radius: 2px;
}
 
/* ====================================
   LEVEL BLOCK
==================================== */
.level-block {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 5px;
    overflow: hidden;
    transition: border-color 0.2s;
}
 
.level-block.level-locked {
    opacity: 0.5;
}
 
.level-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    cursor: pointer;
    user-select: none;
}
 
.level-locked .level-header {
    cursor: default;
}
 
/* Badge angka per level */
.level-badge {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    flex-shrink: 0;
}
.lv1 .level-badge { background: #E1F5EE; color: #0F6E56; }
.lv2 .level-badge { background: #E6F1FB; color: #185FA5; }
.lv3 .level-badge { background: #FAEEDA; color: #854F0B; }
.lv4 .level-badge { background: #FCEBEB; color: #A32D2D; }
 
.level-info {
    flex: 1;
    min-width: 0;
}
 
.level-title {
    font-size: 14px;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 2px;
}
 
.level-desc {
    font-size: 12px;
    color: #6b7280;
}
 
/* Tags */
.level-tag {
    font-size: 10px;
    padding: 3px 9px;
    border-radius: 20px;
    font-weight: 500;
    white-space: nowrap;
}
.tag-done    { background: #E1F5EE; color: #0F6E56; }
.tag-active  { background: #E1F5EE; color: #0F6E56; }
.tag-locked  { background: #f3f4f6; color: #6b7280; }
.tag-hard    { background: #FCEBEB; color: #A32D2D; }
 
/* Chevron */
.level-chevron {
    font-size: 13px;
    color: #9ca3af;
    transition: transform 0.2s;
}
.level-block.is-open .level-chevron {
    transform: rotate(90deg);
}
 
/* ====================================
   LEVEL BODY (expand)
==================================== */
.level-body {
    display: none;
    border-top: 0.5px solid #e5e7eb;
    padding: 1rem 1.25rem;
}
 
.level-block.is-open .level-body {
    display: block;
}
 
/* Progress bar */
.prog-track {
    background: #f3f4f6;
    border-radius: 20px;
    height: 5px;
    overflow: hidden;
    margin-bottom: 6px;
}
 
.prog-fill {
    height: 100%;
    border-radius: 20px;
    transition: width 0.4s ease;
}
.fill-lv1 { background: #1D9E75; }
.fill-lv2 { background: #378ADD; }
.fill-lv3 { background: #EF9F27; }
.fill-lv4 { background: #E24B4A; }
 
.prog-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
 
.prog-label {
    font-size: 12px;
    color: #6b7280;
}
 
.prog-pct {
    font-size: 12px;
    font-weight: 600;
}
.lv1-color { color: #0F6E56; }
.lv2-color { color: #185FA5; }
.lv3-color { color: #854F0B; }
.lv4-color { color: #A32D2D; }
 
/* ====================================
   SUB-TOPIK GRID
==================================== */
.sub-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 8px;
}
 
.sub-card {
    background: #f9fafb;
    border: 0.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.65rem 0.85rem;
    text-decoration: none;
    display: block;
    transition: border-color 0.15s, background 0.15s;
}
.sub-card:hover:not(.sub-locked) {
    border-color: #9ca3af;
    background: #f3f4f6;
}
.sub-card.sub-done  { border-color: #9FE1CB; }
.sub-card.sub-active { border-color: #93C5FD; }
.sub-card.sub-locked { opacity: 0.5; cursor: default; }
 
.sub-name {
    font-size: 12px;
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 3px;
}
 
.sub-meta {
    font-size: 11px;
    color: #6b7280;
}
 
.sub-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    margin-right: 5px;
}
.dot-done   { background: #1D9E75; }
.dot-active { background: #378ADD; }
.dot-locked { background: #d1d5db; }
 
/* ====================================
   RESPONSIVE
==================================== */
@media (max-width: 576px) {
    .resume-card { flex-wrap: wrap; }
    .resume-btn  { width: 100%; text-align: center; }
    .stats-row   { gap: 8px; }
    .stat-num    { font-size: 18px; }
}
</style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>
                @if (auth()->user()->roles === 'administrator')
                    <div class="section-header-button">
                        <a href="{{ route('exercise-level.create') }}" class="btn btn-primary">
                            Tambah
                        </a>
                    </div>
                @endif
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Level</div>
                </div>
            </div>

             <div class="section-body">
                <div class="section-label">Roadmap latihan</div>

                <div class="levels-wrapper">

                    @foreach ($questionLevel as $index => $level)
                        @php
                            $status = data_get($level, 'status', data_get($level, 'is_active', false) ? 'active' : 'locked');
                            $isFinal = data_get($level, 'is_final', false);
                            $progressPercent = data_get($level, 'progress_percent', 0);
                            $progressDone = data_get($level, 'progress_done', 0);
                            $progressTotal = data_get($level, 'progress_total', 0);
                            $subtopics = data_get($level, 'subtopics', []);
                            $levelName = data_get($level, 'name', 'Unnamed Level');
                            $levelDescription = data_get($level, 'description', 'Tidak ada deskripsi.');
                        @endphp

                        {{-- Connector line antar level --}}
                        @if ($index > 0)
                            <div class="connector">
                                <div class="connector-line"></div>
                            </div>
                        @endif

                        <div class="level-block lv{{ $index + 1 }}
                                    {{ $status === 'done' ? 'level-done' : '' }}
                                    {{ $status === 'active' ? 'level-active' : '' }}
                                    {{ $status === 'locked' ? 'level-locked' : '' }}
                                    {{ in_array($status, ['done', 'active']) ? 'is-open' : '' }}"
                            data-level="{{ $index + 1 }}">

                            {{-- Header level (klik untuk expand) --}}
                            <div class="level-header" @if ($status !== 'locked') onclick="toggleLevel(this)" @endif>

                                <div class="level-badge">
                                    @if ($isFinal)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>

                                <div class="level-info">
                                    <div class="level-title">{{ $levelName }}</div>
                                    <div class="level-desc">{{ $levelDescription }}</div>
                                </div>

                                <span
                                    class="level-tag
                    @if ($status === 'done') tag-done
                    @elseif($status === 'active') tag-active
                    @elseif($isFinal) tag-hard
                    @else tag-locked @endif">
                                    @if ($status === 'locked' && !$isFinal)
                                        <i class="fa-solid fa-lock me-1"></i> Terkunci
                                    @elseif($status === 'done')
                                        <i class="fa-solid fa-check me-1"></i> Selesai
                                    @elseif($status === 'active')
                                        Sedang berjalan
                                    @elseif($isFinal)
                                        Tersulit
                                    @endif
                                </span>

                                @if ($status !== 'locked')
                                    <span class="level-chevron">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>

                            {{-- Body level (expand) --}}
                            @if ($status !== 'locked')
                                <div class="level-body">
                                    {{-- Progress bar --}}
                                    <div class="prog-track">
                                        <div class="prog-fill fill-lv{{ $index + 1 }}"
                                            style="width: 100%"></div>
                                    </div>
                                    <div class="prog-row">
                                        <span class="prog-label">{{ $progressDone }} /
                                            {{ $progressTotal }} soal selesai</span>
                                        <span
                                            class="prog-pct lv{{ $index + 1 }}-color">{{ $progressPercent }}%</span>
                                    </div>

                                    {{-- Sub-topik --}}
                                    <div class="sub-grid">
                                        @forelse ($subtopics as $sub)
                                            <a href="{{ $sub['status'] !== 'locked' ? $sub['url'] : '#' }}"
                                                class="sub-card
                           {{ $sub['status'] === 'done' ? 'sub-done' : '' }}
                           {{ $sub['status'] === 'active' ? 'sub-active' : '' }}
                           {{ $sub['status'] === 'locked' ? 'sub-locked' : '' }}"
                                                @if ($sub['status'] === 'locked') onclick="return false" @endif>
                                                <div class="sub-name">
                                                    <span class="sub-dot dot-{{ $sub['status'] }}"></span>
                                                    {{ $sub['name'] }}
                                                </div>
                                                <div class="sub-meta">
                                                    {{ $sub['total'] }} soal
                                                    &bull;
                                                    @if ($sub['status'] === 'done')
                                                        Selesai
                                                    @elseif($sub['status'] === 'active')
                                                        Berjalan
                                                    @else
                                                        Terkunci
                                                    @endif
                                                </div>
                                            </a>
                                        @empty
                                            <div class="sub-card sub-locked">
                                                <div class="sub-name">Belum ada sub-topik</div>
                                                <div class="sub-meta">Tambahkan sub-topik untuk level ini.</div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    </div>


@endsection 
 
@push('scripts')
<script>
function toggleLevel(header) {
    const block = header.closest('.level-block');
    block.classList.toggle('is-open');
}
</script>
@endpush

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
