@extends('layouts.app')

@section('title', 'Grouping Kalimat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
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
                                <small id="merge-error" class="text-danger d-block mt-2" style="display: none;"></small>

                            </div>
                            <div class="card-footer">
                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" id="btn-unselect"
                                        class="btn btn-secondary mr-2">Bersihkan</button>
                                    <form id="merge-form" action="{{ route('word_groups.merge') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ids" id="selected-ids">
                                        <button type="submit"
                                            class="btn btn-icon icon-left btn-success btn-lg mr-2 disabled"
                                            id="btn-merge">Gabungkan
                                        </button>
                                    </form>
                                    <form id="split-form" action="{{ route('word_groups.split') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="split-id">
                                        <button type="submit"
                                            class="btn btn-icon icon-left btn-warning btn-lg mr-2 disabled"
                                            id="btn-split">Pisahkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-lg mr-2" id="btn-prev-verse"><i
                                    class="ion-chevron-left" data-pack="default" data-tags="arrow, left"></i></button>
                            <button type="button" class="btn btn-primary btn-lg" id="btn-next-verse">
                                <i class="ion-chevron-right" data-pack="default" data-tags="arrow, right"></i></button>
                        </div>
                        <div>
                            <form id="complete-form" action="{{ route('word_groups.complete') }}" method="POST" class="ml-auto">
                                @csrf
                                <input type="hidden" name="surah_id" value="{{ request('surah_id') }}">
                                <input type="hidden" name="verse_number" value="{{ request('verse_number') }}">
                                <button type="submit" class="btn btn-success btn-lg" id="btn-complete">Selesai dan
                                    lanjutkan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // =============================
            // VARIABEL GLOBAL
            // =============================
            const mergeButton = document.getElementById('btn-merge');
            const splitButton = document.getElementById('btn-split');
            const btnUnselect = document.getElementById('btn-unselect');
            const idsInput = document.getElementById('selected-ids');
            const splitIdInput = document.getElementById('split-id');
            const mergeForm = document.getElementById('merge-form');
            const splitForm = document.getElementById('split-form');
            const errorMsg = document.getElementById('merge-error');
            const wordgroupList = document.getElementById('wordgroup-list');
            const surahSelect = document.getElementById('surah-select');
            const verseSelect = document.getElementById('verse-select');
            const resultVerse = document.getElementById('result-verse');
            const filterForm = document.getElementById('filter-form');
            const btnPrev = document.getElementById('btn-prev-verse');
            const btnNext = document.getElementById('btn-next-verse');
            const btnComplete = document.getElementById('btn-complete');

            // =============================
            // FUNGSI UTILITAS
            // =============================

            // Ambil checkbox terbaru dari DOM
            function getCheckboxes() {
                return document.querySelectorAll('.row-checkbox');
            }

            function showError(message) {
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                }, 3000);
            }

            // =============================
            // FUNGSI MANAJEMEN CHECKBOX
            // =============================

            function updateMergeButton() {
                const checkboxes = getCheckboxes();
                const checkedCount = Array.from(checkboxes).filter(x => x.checked).length;

                if (checkedCount >= 2) {
                    mergeButton.classList.remove('disabled');
                    splitButton.disabled = false;
                } else {
                    mergeButton.classList.add('disabled');
                    splitButton.disabled = true;
                }
            }

            function updateSplitButton() {
                const checkboxes = getCheckboxes();
                const checked = Array.from(checkboxes).filter(x => x.checked);
                if (checked.length === 1) {
                    splitButton.classList.remove('disabled');
                    splitButton.disabled = false;
                    splitIdInput.value = checked[0].value;
                } else {
                    splitButton.classList.add('disabled');
                    splitButton.disabled = true;
                    splitIdInput.value = '';
                }
            }

            // Pasang event listener ke semua checkbox (baik awal maupun setelah fetch)
            function bindCheckboxEvents() {
                const checkboxes = getCheckboxes();
                checkboxes.forEach(cb => cb.addEventListener('change', () => {
                    updateMergeButton();
                    updateSplitButton();
                }));
            }

            // =============================
            // FUNGSI UNTUK MERGE
            // =============================

            function handleMergeSubmit(e) {
                e.preventDefault();

                const checkboxes = getCheckboxes();
                const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
                const selectedIds = Array.from(checkboxes)
                    .filter(x => x.checked)
                    .map(x => x.value);
                const selectedTexts = selectedCheckboxes.map(cb => {
                    const btn = cb.closest('.selectgroup-item')?.querySelector(
                        '.selectgroup-button');
                    return btn ? btn.textContent.trim() : '';
                });

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';

                if (mergeButton.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedIds.length < 2) {
                    e.preventDefault();
                    alert('Pilih minimal 2 baris untuk merge');
                    return;
                }

                // CEK URUTAN — tidak boleh lompat
                const selectedIndexes = Array.from(checkboxes)
                    .map((cb, i) => cb.checked ? i : null)
                    .filter(i => i !== null);

                const isSequential = selectedIndexes.every((val, i, arr) =>
                    i === 0 || val - arr[i - 1] === 1
                );

                if (!isSequential) {
                    e.preventDefault();
                    showError('Kalimat harus berurutan dan tidak boleh lompat');
                    return;
                }

                // buat preview (batasi panjang agar popup tidak kebanyakan teks)
                const previewTextFull = selectedTexts.join(' ');
                const previewText = previewTextFull.length > 200 ? previewTextFull.slice(0, 200) + '…' :
                    previewTextFull;

                swal({
                    title: previewText,
                    text: 'Yakin ingin menggabungkan?',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Gabungkan',
                            visible: true,
                            className: 'btn btn-success'
                        }
                    },
                    dangerMode: true,
                }).then((willMerge) => {
                    if (willMerge) {
                        idsInput.value = selectedIds.join(',');
                        mergeForm.submit();
                    }
                });
            }

            // =============================
            // FUNGSI UNTUK SPLIT
            // =============================

            function handleSplitSubmit(e) {
                e.preventDefault();

                const checkboxes = getCheckboxes();
                const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';

                if (splitButton.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedCheckboxes.length !== 1) {
                    e.preventDefault();
                    alert('Pilih tepat 1 baris untuk split');
                    return;
                }

                const selectedText = selectedCheckboxes.map(cb => {
                    const btn = cb.closest('.selectgroup-item')?.querySelector('.selectgroup-button');
                    return btn ? btn.textContent.trim() : '';
                })[0];

                // buat preview (batasi panjang agar popup tidak kebanyakan teks)
                const previewText = selectedText.length > 200 ? selectedText.slice(0, 200) + '…' : selectedText;

                swal({
                    title: previewText,
                    text: 'Yakin ingin memisahkan kalimat ini?',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Pisahkan',
                            visible: true,
                            className: 'btn btn-success'
                        }
                    },
                    dangerMode: true,
                }).then((willSplit) => {
                    if (willSplit) {
                        splitForm.submit();
                    }
                });
            }

            // =============================
            // FUNGSI UNTUK FILTER SURAH & AYAT
            // =============================

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
                        updateSplitButton(); // Jangan lupa update split button juga
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

            function handleFilterSubmit(e) {
                e.preventDefault();
                fetchVerse(surahSelect.value, verseSelect.value);
            }

            // =============================
            // FUNGSI NAVIGASI AYAT
            // =============================

            function goToPrevVerse() {
                let current = parseInt(verseSelect.value);
                if (current > 1) {
                    verseSelect.value = current - 1;
                    fetchVerse(surahSelect.value, verseSelect.value);
                }
            }

            function goToNextVerse() {
                let current = parseInt(verseSelect.value);
                const max = verseSelect.options.length;
                if (current < max) {
                    verseSelect.value = current + 1;
                    fetchVerse(surahSelect.value, verseSelect.value);
                }
            }

            // =============================
            // INISIALISASI EVENT LISTENER
            // =============================

            // Event listener untuk checkbox
            bindCheckboxEvents();

            // Event listener untuk tombol unselect
            btnUnselect.addEventListener('click', function() {
                const checkboxes = getCheckboxes();
                checkboxes.forEach(cb => cb.checked = false);
                updateMergeButton();
                updateSplitButton(); // Update split button juga

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';
            });

            // Event listener untuk form merge
            mergeForm.addEventListener('submit', handleMergeSubmit);

            // Event listener untuk form split
            splitForm.addEventListener('submit', handleSplitSubmit);

            // Event listener untuk form filter
            filterForm.addEventListener('submit', handleFilterSubmit);

            // Event listener untuk perubahan surah
            surahSelect.addEventListener('change', function() {
                updateVerseOptions();
                verseSelect.selectedIndex = 0;
            });

            // Event listener untuk navigasi ayat
            btnPrev.addEventListener('click', goToPrevVerse);
            btnNext.addEventListener('click', goToNextVerse);

            // =============================
            // INISIALISASI AWAL
            // =============================

            // Inisialisasi opsi ayat jika surah sudah dipilih
            if (surahSelect.value) updateVerseOptions();

            // Inisialisasi status tombol
            updateMergeButton();
            updateSplitButton();
        });
    </script>
@endpush
