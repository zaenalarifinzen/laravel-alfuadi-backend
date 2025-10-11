@extends('layouts.app')

@section('title', 'Grouping Kalimat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        /* Font Arab */
        @import url('https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&family=Amiri&display=swap');

        .arabic-container {
            direction: rtl;
            text-align: right;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.5rem;
        }


        /* Gunakan font arab */
        .arabic-text {
            font-family: 'Scheherazade New', 'Amiri', serif;
            font-size: 1.8rem;
            line-height: 2.2rem;
            direction: rtl;
        }

        /* Pastikan form tetap LTR */
        .ltr-container {
            direction: ltr !important;
            text-align: left !important;
        }

        /* Reset untuk tampilan select agar tidak terpengaruh selectric.css */
        .ltr-container select.form-control {
            border-radius: 0 !important;
            height: calc(2.25rem + 2px) !important;
        }

        /* Pastikan tombol kanan tidak terpotong */
        /* .ltr-container .input-group-append .btn {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 0.35rem !important;
            border-bottom-right-radius: 0.35rem !important;
        } */

        /* Agar input-group tidak ada overflow tersembunyi */
        .ltr-container .input-group {
            overflow: visible !important;
            flex-wrap: nowrap !important;
        }

        /* Supaya tombol tidak menumpuk di atas border input */
        .ltr-container .btn {
            z-index: 1;
        }


        /* Sesuaikan chip agar proporsional dengan tinggi huruf Arab */
        .selectgroup.selectgroup-pills .selectgroup-item .selectgroup-button {
            border-radius: 16px;
            padding: 0.4rem 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: auto;
            min-height: 2.4rem;
            transition: all 0.2s ease-in-out;
        }

        /* Hover */
        /* .selectgroup.selectgroup-pills .selectgroup-item:hover .selectgroup-button {
                                                                                                                    background-color: #95a0ee;
                                                                                                                } */

        /* Saat terpilih */
        .selectgroup-input:checked+.selectgroup-button {
            background-color: #6777ef;
            color: #fff;
            border-color: #6777ef;
        }

        /* Supaya chip rapi dalam arah kanan ke kiri */
        .selectgroup.selectgroup-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            justify-content: flex-start;
        }

        /* Batasi efek RTL hanya untuk teks Arab */
        .arabic-container {
            direction: rtl;
            text-align: right;
        }

        /* Wrapper untuk form agar tidak ikut RTL */
        .ltr-container {
            direction: ltr !important;
            text-align: left !important;
        }

        /* Pastikan input group tetap gaya Bootstrap */
        .ltr-container .input-group {
            display: flex;
            align-items: stretch;
            gap: 0;
            /* biarkan Bootstrap handle */
        }


        .card-header .input-group {
            display: flex !important;
            justify-content: flex-start !important;
            align-items: center !important;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Grup Kalimat</h1>

                <div class="ltr-container">
                    <form method="GET" action="{{ route('wordgroups.indexByVerse') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control select2" name="surah_id" id="surah-select"
                                style="flex: 5; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;"
                                required>
                                @foreach ($surahs as $surah)
                                    <option value="{{ $surah->id }}" data-verse-count="{{ $surah->verse_count }}"
                                        {{ request('surah_id') == $surah->id ? 'selected' : '' }}>
                                        {{ $surah->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select class="form-control select2" name="verse_number" id="verse-select" style="flex: 2;"
                                required>
                                <option value="">Pilih Ayat</option>
                            </select>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 id="result-verse" class="mb-0">Ayat Terpilih</h4>
                            </div>


                            <div class="card-body">
                                <div class="selectgroup selectgroup-pills arabic-container " dir="rtl"
                                    id="wordgroup-list">
                                    @foreach ($wordgroups as $wg)
                                        <label class="selectgroup-item arabic-pill">
                                            <input type="checkbox" name="ids[]" value="{{ $wg->id }}"
                                                class="selectgroup-input row-checkbox">
                                            <span class="selectgroup-button arabic-text">{{ $wg->text }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="clearfix mb-3"></div>

                            </div>
                            <div class="card-footer">
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" id="btn-unselect" class="btn btn-secondary mr-2"><i
                                            class="fas fa-xmark"></i> Bersihkan
                                        Pilihan</button>
                                    <form id="merge-form" action="{{ route('word_groups.merge') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ids" id="selected-ids">
                                        <button type="submit" class="btn btn-icon icon-left btn-warning btn-lg disabled"
                                            id="btn-merge">Gabungkan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-end">
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-prev-verse">Sebelumnya</button>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-next-verse">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script src="{{ asset('js/page/modules-toastr.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mergeButton = document.getElementById('btn-merge');
            const btnUnselect = document.getElementById('btn-unselect');
            const idsInput = document.getElementById('selected-ids');
            const mergeForm = document.getElementById('merge-form');
            const wordgroupList = document.getElementById('wordgroup-list');

            // Ambil checkbox terbaru dari DOM
            function getCheckboxes() {
                return document.querySelectorAll('.row-checkbox');
            }

            btnUnselect.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => cb.checked = false); // hapus semua centang
                updateMergeButton();
            });

            function updateMergeButton() {
                const checkboxes = getCheckboxes();
                const checkedCount = Array.from(checkboxes).filter(x => x.checked).length;

                if (checkedCount >= 2) {
                    mergeButton.classList.remove('disabled');
                } else {
                    mergeButton.classList.add('disabled');
                }
            }

            // 🔁 Pasang event listener ke semua checkbox (baik awal maupun setelah fetch)
            function bindCheckboxEvents() {
                const checkboxes = getCheckboxes();
                checkboxes.forEach(cb => cb.addEventListener('change', updateMergeButton));
            }

            bindCheckboxEvents(); // pertama kali saat halaman load

            mergeForm.addEventListener('submit', (e) => {
                const checkboxes = getCheckboxes();
                const selectedIds = Array.from(checkboxes)
                    .filter(x => x.checked)
                    .map(x => x.value);

                if (mergeButton.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedIds.length < 2) {
                    e.preventDefault();
                    alert('Pilih minimal 2 baris untuk merge');
                    return;
                }

                if (!confirm('Yakin ingin merge baris ini?')) {
                    e.preventDefault();
                    return;
                }

                idsInput.value = selectedIds.join(',');
            });


            /** ------------------------------
             *  BAGIAN 2 — FILTER SURAH & AYAT
             * ------------------------------ */
            const surahSelect = document.getElementById('surah-select');
            const verseSelect = document.getElementById('verse-select');
            const resultVerse = document.getElementById('result-verse');
            const filterForm = document.getElementById('filter-form');
            const surahId = surahSelect.value;
            const verseNum = verseSelect.value;

            function fetchVerse(surahId, verseNum) {
                if (!surahId || !verseNum) {
                    alert('Pilih Surah dan Ayat terlebih dahulu!');
                    return;
                }

                fetch(
                        `{{ route('wordgroups.indexByVerse') }}?surah_id=${surahId}&verse_number=${verseNum}`
                    )
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newList = doc.querySelector('#wordgroup-list');

                        if (newList) {
                            wordgroupList.innerHTML = newList.innerHTML;
                        } else {
                            wordgroupList.innerHTML =
                                '<p class="text-muted">Tidak ada data untuk ayat ini.</p>';
                        }

                        // Update URL di address bar tanpa reload
                        const params = new URLSearchParams({
                            surah_id: surahId,
                            verse_number: verseNum
                        });
                        history.pushState({}, '', `?${params.toString()}`);

                        updateResultText();
                        bindCheckboxEvents(); // Re-bind event checkbox baru setelah data dimuat
                        updateMergeButton();
                    })
                    .catch(err => {
                        console.error(err);
                        wordgroupList.innerHTML =
                            '<p class="text-danger">Terjadi kesalahan mengambil data.</p>';
                    });
            }

            function updateVerseOptions() {
                const selected = surahSelect.options[surahSelect.selectedIndex];
                const verseCount = selected ? selected.getAttribute('data-verse-count') : 0;
                const currentVerse = "{{ request('verse_number') }}";
                verseSelect.innerHTML = '<option value="1">1</option>';
                for (let i = 2; i <= verseCount; i++) {
                    verseSelect.innerHTML +=
                        `<option value="${i}" ${currentVerse == i ? 'selected' : ''}>${i}</option>`;
                }
            }

            function updateResultText() {
                const surahName = surahSelect.options[surahSelect.selectedIndex]?.text || '';
                const verseNumber = verseSelect.value;
                if (surahName && verseNumber) {
                    resultVerse.textContent = `${surahName} - Ayat ${verseNumber}`;
                } else if (surahName) {
                    resultVerse.textContent = `${surahName}`;
                } else {
                    resultVerse.textContent = 'Ayat Terpilih';
                }
            }


            // Tangkap submit form (Cari)
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                fetchVerse(surahSelect.value, verseSelect.value);
            });

            surahSelect.addEventListener('change', function() {
                updateVerseOptions();
                verseSelect.selectedIndex = 0;
            });

            if (surahSelect.value) updateVerseOptions();

            /** ------------------------------
             *  BAGIAN 3 — CONTROLLER AYAT
             * ------------------------------ */
            const btnPrev = document.getElementById('btn-prev-verse');
            const btnNext = document.getElementById('btn-next-verse');

            // Tombol Sebelumnya
            btnPrev.addEventListener('click', function() {
                let current = parseInt(verseSelect.value);
                if (current > 1) {
                    verseSelect.value = current - 1;
                    fetchVerse(surahSelect.value, verseSelect.value);
                }
            });

            // Tombol Selanjutnya
            btnNext.addEventListener('click', function() {
                let current = parseInt(verseSelect.value);
                const max = verseSelect.options.length;
                if (current < max) {
                    verseSelect.value = current + 1;
                    fetchVerse(surahSelect.value, verseSelect.value);
                }
            });
        });
    </script>
@endpush
