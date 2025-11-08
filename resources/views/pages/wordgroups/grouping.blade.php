@extends('layouts.app')

@section('title', 'Grouping Kalimat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
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
            border-radius: 8px !important;
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
            background-color: #d6f7f0 !important;
            color: #1a1a1a !important;
            border-color: #259980;
            border-width: 1px;
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

        #button-bar {
            transition: opacity 0.25s ease, box-shadow 0.25s ease;
        }

        #button-bar:not(.floating) {
            opacity: 1;
        }

        #button-bar.floating {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.95;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: auto;
            max-width: fit-content;
            padding: 8px 10px;
            display: flex;
            flex-wrap: nowrap;
            z-index: 999;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Grup Kalimat</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control form-control-sm" name="surah-option" id="surah-option"
                                style="flex: 3; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;"
                                required>
                                <option value="">Pilih Surah</option>
                                @foreach ($surahs as $surah)
                                    <option value="{{ $surah->id }}" data-verse-count="{{ $surah->verse_count }}">
                                        {{ $surah->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control" placeholder="Ayat" name="verse-option"
                                id="verse-option" value="" style="flex: 1;" required>

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
                                <input type="hidden" id="surah-id" value="{{ $currentSurah->id }}">
                                <input type="hidden" id="verse-number" value="{{ $currentVerse->number }}">
                                <input type="hidden" id="verse-id" value="{{ $currentVerse->id }}">
                                <input type="hidden" id="isPersisted" value="{{ $isPersisted ? 1 : 0 }}">
                                <h4 id="result-verse" class="mb-0">{{ $currentSurah->name ?? 'Al-Fatihah' }} - Ayat
                                    {{ $currentVerse->number ?? 1 }}</h4>


                            </div>


                            <div class="card-body">
                                <div class="selectgroup selectgroup-pills arabic-container " dir="rtl"
                                    id="wordgroup-list" data-is-persisted="{{ $isPersisted ? '1' : '0' }}"
                                    data-surah-name="{{ $currentSurah->name }}"
                                    data-verse-count="{{ $currentSurah->verse_count }}"
                                    data-verse-number="{{ $currentVerse->number }}">
                                    @foreach ($words as $index => $word)
                                        <label class="selectgroup-item arabic-pill">
                                            <input type="checkbox" name="ids[]" value="{{ $word->id }}"
                                                class="selectgroup-input row-checkbox">
                                            <span class="selectgroup-button arabic-text">{{ $word->text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="editor" style="padding-top: 20px">
                                    <span>Editor : </span>
                                    <a href="#"
                                        class="font-weight-600">{{ $words->first()->editorInfo->name ?? '-' }}</a>
                                </div>

                                <div class="clearfix mb-3"></div>
                                <div id="button-bar-sentinel"></div>
                                <small id="merge-error" class="text-danger d-block mt-2" style="display: none;"></small>

                            </div>
                            <div class="card-footer">
                                <div id="button-bar"
                                    class="d-flex gap-2 mb-3 justify-content-center align-items-center flex-nowrap">
                                    <button type="submit" id="btn-unselect" class="btn btn-icon btn-lg btn-secondary"
                                        data-toggle="tooltip" data-placement="top" title="Bersihkan Pilihan"><i
                                            class="fa-regular fa-circle-xmark"></i></button>
                                    <button type="submit" id="btn-edit" class="btn btn-icon btn-lg btn-info disabled"
                                        data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa-solid fa-pencil"></i></button>
                                    <form id="split-form" action="{{ route('wordgroups.split') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="split-id">
                                        <button type="submit" class="btn btn-icon btn-lg btn-warning disabled"
                                            id="btn-split" data-toggle="tooltip" data-placement="top"
                                            title="Pisahkan"><i class="fa-solid fa-scissors"></i>
                                        </button>
                                    </form>
                                    <form id="merge-form" action="{{ route('wordgroups.merge') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ids" id="selected-ids">
                                        <button type="submit" class="btn btn-icon btn-lg btn-success disabled"
                                            id="btn-merge" data-toggle="tooltip" data-placement="top"
                                            title="Gabungkan"><i class="fa-solid fa-magnet"></i>
                                        </button>
                                    </form>
                                </div>
                                <div id="button-bar-sentinel"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                                <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button>
                            <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                    class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button>
                        </div>
                        <button id="btn-test-save" class="btn btn-warning">Test Save JSON</button>
                        <div>
                            <form id="complete-form" action="{{ route('wordgroups.save') }}" method="POST"
                                class="ml-auto">
                                @csrf
                                <input type="hidden" name="surah_id" value="{{ request('surah_id') }}">
                                <input type="hidden" name="verse_number" value="{{ request('verse_number') }}">
                                <button type="submit" class="btn btn-primary btn-lg" id="btn-complete">
                                    {{ $isPersisted ? 'Update' : 'Simpan & lanjutkan' }}
                                </button>
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // =============================
            // VARIABEL GLOBAL
            // =============================
            const btnUnselect = document.getElementById('btn-unselect');
            const btnEdit = document.getElementById('btn-edit');
            const btnMerge = document.getElementById('btn-merge');
            const btnSplit = document.getElementById('btn-split');
            const btnPrev = document.getElementById('btn-prev-verse');
            const btnNext = document.getElementById('btn-next-verse');
            const btnComplete = document.getElementById('btn-complete');
            const btnTestSave = document.getElementById('btn-test-save');

            const splitIdInput = document.getElementById('split-id');

            const filterForm = document.getElementById('filter-form');
            const mergeForm = document.getElementById('merge-form');
            const splitForm = document.getElementById('split-form');

            const surahOption = document.getElementById('surah-option');
            const verseOption = document.getElementById('verse-option');
            const wordgroupList = document.getElementById('wordgroup-list');
            const currentVerseId = document.getElementById('verse-id');
            const currentSurahId = document.getElementById('surah-id');
            const currentVerseNumber = document.getElementById('verse-number');

            const resultLabel = document.getElementById('result-verse');
            const errorMsg = document.getElementById('merge-error');
            let modified = false;
            let verseCount;

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

            function updatebtnMerge() {
                const checkboxes = getCheckboxes();
                const checkedCount = Array.from(checkboxes).filter(x => x.checked).length;

                if (checkedCount >= 2) {
                    btnMerge.classList.remove('disabled');
                    btnSplit.disabled = false;
                } else {
                    btnMerge.classList.add('disabled');
                    btnSplit.disabled = true;
                }
            }

            function updatebtnEditAndSplit() {
                const checkboxes = getCheckboxes();
                const checked = Array.from(checkboxes).filter(x => x.checked);
                if (checked.length === 1) {
                    btnEdit.classList.remove('disabled');
                    btnSplit.classList.remove('disabled');
                    btnEdit.disabled = false;
                    btnSplit.disabled = false;
                    splitIdInput.value = checked[0].value;
                } else {
                    btnEdit.classList.add('disabled');
                    btnSplit.classList.add('disabled');
                    btnEdit.disabled = true;
                    btnSplit.disabled = true;
                    splitIdInput.value = '';
                }
            }

            // Pasang event listener ke semua checkbox (baik awal maupun setelah fetch)
            function bindCheckboxEvents() {
                const checkboxes = getCheckboxes();
                checkboxes.forEach(cb => cb.addEventListener('change', () => {
                    updatebtnMerge();
                    updatebtnEditAndSplit();
                }));
            }

            // =============================
            // FUNGSI UNTUK MERGE
            // =============================

            function handleMergeSubmit(e) {
                e.preventDefault();

                const checkboxes = getCheckboxes();
                const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';

                if (btnMerge.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedCheckboxes.length < 2) {
                    e.preventDefault();
                    alert('Pilih minimal 2 baris untuk digabungkan');
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
                    iziToast.warning({
                        message: 'Kalimat harus berurutan dan tidak boleh lompat',
                        position: 'topRight'
                    });
                    return;
                }

                // Gabungkan text
                const selectedText = selectedCheckboxes.map(cb => {
                    const btn = cb.closest('.selectgroup-item').querySelector('.selectgroup-button');
                    return btn.textContent.trim();
                });
                const combinedText = selectedText.join(' ');

                const firstLabel = selectedCheckboxes[0].closest('.selectgroup-item');
                const newLabel = firstLabel.cloneNode(true);

                const newInput = newLabel.querySelector('.selectgroup-input');
                const newButton = newLabel.querySelector('.selectgroup-button');

                newInput.checked = false;
                newInput.value = 'merge-${Date.now()}';
                newButton.textContent = combinedText;

                // Sisipkan hasil merge sebelum label pertama
                wordgroupList.insertBefore(newLabel, firstLabel);

                // Hapus semua label yang tergabung
                selectedCheckboxes.forEach(cb => cb.closest('.selectgroup-item').remove());

                modified = true;

                // Re-bind event ke elemen baru
                bindCheckboxEvents();
                updatebtnMerge();
                updatebtnEditAndSplit();

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

                if (btnSplit.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedCheckboxes.length !== 1) {
                    alert('Pilih 1 kalimah untuk dipisah');
                    return;
                }

                const selectedCheckbox = selectedCheckboxes[0];
                const label = selectedCheckbox.closest('.selectgroup-item');
                const textButton = label.querySelector('.selectgroup-button');
                const originalText = textButton.textContent.trim();

                const words = originalText.split(' ').filter(w => w.trim() !== '');
                if (words.length <= 1) {
                    iziToast.warning({
                        message: 'Tidak bisa memisah 1 kalimat',
                        position: 'topRight'
                    });
                    return;
                }

                // Tambahkan elemen baru untuk setiap kata
                words.forEach(word => {
                    const newLabel = label.cloneNode(true);
                    const newInput = newLabel.querySelector('.selectgroup-input');
                    const newButton = newLabel.querySelector('.selectgroup-button');

                    newInput.checked = false;
                    newInput.value =
                        `split-${Date.now()}-${Math.random().toString(36).substring(2, 7)}`;
                    newButton.textContent = word;

                    // Sisipkan sebelum label lama agar urutan tetap benar
                    wordgroupList.insertBefore(newLabel, label);
                });

                // Hapus label lama
                label.remove();

                modified = true;

                // Re-bind event ke elemen baru
                bindCheckboxEvents();
                updatebtnMerge();
                updatebtnEditAndSplit();
            }

            // =============================
            // FUNGSI UNTUK EDIT KALIMAT
            // =============================

            async function handleEditSubmit(e) {
                e.preventDefault();

                const checkboxes = getCheckboxes();
                const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';

                if (btnEdit.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedCheckboxes.length !== 1) {
                    alert('Pilih 1 kalimah untuk diedit');
                    return;
                }

                const selectedCheckbox = selectedCheckboxes[0];
                const label = selectedCheckbox.closest('.selectgroup-item');
                const textButton = label.querySelector('.selectgroup-button');
                const currentText = `  ${textButton.textContent}  `;

                const newText = await swal({
                    title: 'Edit Kalimat',
                    content: {
                        element: 'input',
                        attributes: {
                            className: 'swal-content__input arabic-text',
                            placeholder: 'Masukkan teks',
                            value: currentText,
                            type: 'text',

                        },
                    },
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                            // className: 'btn btn-succes'
                        },
                        confirm: {
                            text: 'Submit',
                            visible: true,
                            className: 'btn-success'
                        }
                    },
                });

                if (!newText) return;

                // Set teks baru pada elemen yang sama
                textButton.textContent = newText.trim();

                modified = true;

                // Re-bind / update state
                bindCheckboxEvents();
                updatebtnMerge();
                updatebtnEditAndSplit();
            }

            // =============================
            // FUNGSI FETCH WORD GROUPS
            // =============================
            function fetchWordGroups(surah_id, verse_number, verse_id) {
                let url;

                if (verse_id) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace(':id', verse_id);
                } else if (surah_id && verse_number) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace('/:id',
                        `?surah_id=${surah_id}&verse_number=${verse_number}`);
                } else {
                    alert('Parameter tidak lengkap');
                    return
                }

                wordgroupList.innerHTML = '<p class="text-info text-center">Loading...</p>';

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        if (!response.success || !response.data) {
                            wordgroupList.innerHTML =
                                '<p class="text-info text-center">Data tidak ditemukan</p>';
                        }

                        const data = response.data;
                        const isPersisted = data.isPersisted;
                        const wordGroups = data.wordGroups || [];
                        const surah = data.surah;
                        const verse = data.verse;

                        if (wordGroups.length > 0) {
                            let html = '';
                            wordGroups.forEach(wordGroup => {
                                html += `
                                    <label class="selectgroup-item arabic-pill">
                                        <input type="checkbox" name="ids[]" value="${wordGroup.id}" class="selectgroup-input row-checkbox">
                                        <span class="selectgroup-button arabic-text">${wordGroup.text}</span>
                                    </label>
                                `;
                            });

                            wordgroupList.innerHTML = html;

                            wordgroupList.dataset.isPersisted = isPersisted ? '1' : '0';
                            wordgroupList.dataset.surahName = surah.name;
                            wordgroupList.dataset.verseCount = surah.verse_count;
                            wordgroupList.dataset.verseNumber = verse.number;

                            const firstGroup = wordGroups[0];
                            const editorName = firstGroup.editor_info ? firstGroup.editor_info.name :
                                ' -';
                            $('.editor a').contents().last()[0].textContent = ` ${editorName}`;
                        } else {
                            wordgroupList.innerHTML =
                                '<p class="text-muted">Tidak ada data kata untuk ayat ini.</p>'
                        }

                        if (btnComplete) {
                            btnComplete.textContent = isPersisted ? 'Update' : 'Simpan & Lanjutkan';
                        }

                        if (typeof updateResultText === 'function') {
                            updateResultText(surah.name, verse.number);
                        }

                        currentVerseId.value = verse.id;
                        currentSurahId.value = surah.id;
                        currentVerseNumber.value = verse.number;
                        console.log('Verse ID saat ini: ', currentVerseId.value);


                        const completeForm = document.getElementById('complete-form');
                        if (completeForm) {
                            const surahInput = completeForm.querySelector('input[name="surah_id"]');
                            const verseInput = completeForm.querySelector('input[name="verse_number"]');
                            if (surahInput) surahInput.value = surah.id;
                            if (verseInput) verseInput.value = verse.number;
                        }

                        bindCheckboxEvents();
                        updatebtnMerge();
                        updatebtnEditAndSplit();
                        updateVerseCount();

                        surahOption.value = '';
                        verseOption.value = '';

                        const params = new URLSearchParams({
                            surah_id: surah.id,
                            verse_number: verse.number,
                        });
                        history.pushState({}, '', `?${params.toString()}`);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        wordgroupList.innerHTML = '<p class="text-muted">Terjadi kesalahan</p>'
                    }
                })
            }

            function updateResultText(surahName, verseNumber) {
                const resultLabel = document.getElementById('result-verse');
                if (!resultLabel) return;

                if (surahName && verseNumber) {
                    resultLabel.textContent = `${surahName} - Ayat ${verseNumber}`;
                } else if (surahName) {
                    resultLabel.textContent = surahName;
                } else {
                    resultLabel.textContent = 'Ayat Terpilih';
                }
            }


            function showEditConfirmation() {
                return swal({
                    icon: 'warning',
                    title: 'Perubahan belum disimpan',
                    text: 'Abaikan perubahan yang sudah ada?',
                    buttons: {
                        cancel: {
                            text: 'Kembali',
                            visible: true,
                        },
                        confirm: {
                            text: 'Abaikan',
                            visible: true,
                            className: 'btn-success'
                        }
                    },
                });
            }

            function updateVerseCount() {
                const selected = surahOption.options[surahOption.selectedIndex];
                verseCount = selected ? selected.getAttribute('data-verse-count') : 0;

            }

            async function handleFilterSubmit(e) {
                e.preventDefault();

                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                fetchWordGroups(surahOption.value, verseOption.value);
                modified = false;
                // console.log(`Surah Id = ${surahOption.value} Verse = ${verseOption.value}`)
            }

            // =============================
            // FUNGSI NAVIGASI AYAT
            // =============================

            async function goToPrevVerse() {
                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                let verseId = parseInt(currentVerseId.value);
                // console.log(`Verse Id: ${verseId}`);

                if (verseId > 1) {
                    currentVerseId.value = verseId - 1;
                    fetchWordGroups(null, null, currentVerseId.value);
                    modified = false;
                }
            }

            async function goToNextVerse() {
                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                let verseId = parseInt(currentVerseId.value);
                const max = 6236;

                // console.log(`Max: ${max}`);

                if (verseId < max) {
                    currentVerseId.value = verseId + 1;
                    fetchWordGroups(null, null, currentVerseId.value);
                    modified = false;
                }
            }

            // =============================
            // FUNGSI SIMPAN AYAT
            // =============================
            function save() {
                swal({
                    title: 'Konfirmasi',
                    text: 'Yakin ingin menyimpan ayat ini?',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                        },
                        confirm: {
                            text: 'Simpan',
                            visible: true,
                            className: 'btn-success'
                        },
                    },
                }).then((willSave) => {
                    if (!willSave) return;

                    const groups = Array.from(document.querySelectorAll('.selectgroup-input')).map(input =>
                        ({
                            id: input.value,
                            text: input.closest('.selectgroup-item').querySelector(
                                    '.selectgroup-button')
                                .textContent.trim()
                        }));

                    fetch('/wordgroups/save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute(
                                        'content')
                            },
                            body: JSON.stringify({
                                surah_id: currentSurahId.value,
                                verse_number: currentVerseNumber.value,
                                verse_id: currentVerseId.value,
                                groups: groups
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                nextVerseId = parseInt(currentVerseId.value) + 1;
                                console.log(`next verse id: ${nextVerseId}`)
                                window.location.href =
                                    `/wordgroups/grouping?verse_id=${nextVerseId}`;
                            } else {
                                swal('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            swal('Error', 'Gagal menyimpan data.', 'error');
                        });
                });
            }

            // TEST SAVE
            function saveOrUpdate() {
                swal({
                    title: 'Konfirmasi',
                    text: 'Yakin ingin menyimpan ayat ini?',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                        },
                        confirm: {
                            text: 'Simpan',
                            visible: true,
                            className: 'btn-success'
                        },
                    },
                }).then((willSave) => {
                    if (!willSave) return;

                    const testPayload = {
                        verse_id: 12,
                        surah_id: 2,
                        verse_number: 255,
                        edited_groups: [{
                            id: 1,
                            order_number: 1,
                            text: 'Grup 1 Grup 2 Grup 3',
                            note: 'edited'
                        }],
                        merged_map: {
                            2: 1,
                            3: 1
                        },
                        deleted_ids: [2, 3],
                        new_groups: [{
                            text: 'New',
                            note: 'added'
                        }]
                    };

                    $.ajax({
                        url: "{{ route('wordgroups.update') }}",
                        method: "POST",
                        data: JSON.stringify(testPayload),
                        contentType: "application/json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('✅ Success:', response);
                            alert('Berhasil dikirim! Cek console/log untuk hasil.');
                        },
                        error: function(xhr) {
                            console.error('❌ Error:', xhr.responseText);
                            alert('Gagal kirim data, periksa console.');
                        }
                    });
                });
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
                updatebtnMerge();
                updatebtnEditAndSplit(); // Update split button juga

                // Reset pesan error
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';
            });

            // Event listener untuk form merge
            btnEdit.addEventListener('click', handleEditSubmit);

            // Event listener untuk form merge
            mergeForm.addEventListener('submit', handleMergeSubmit);

            // Event listener untuk form split
            splitForm.addEventListener('submit', handleSplitSubmit);

            // Event listener untuk form filter
            filterForm.addEventListener('submit', handleFilterSubmit);

            // Event listener untuk perubahan surah
            surahOption.addEventListener('change', function() {
                updateVerseCount();
                verseOption.value = 1;
            });

            // Event listener untuk batasi jumlah ayat
            verseOption.addEventListener('change', function() {
                if (parseInt(verseOption.value) > verseCount) {
                    verseOption.value = verseCount;
                }
            });

            // Event listener untuk simpan
            const completeForm = document.getElementById('complete-form');
            if (completeForm) {
                completeForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // cegah submit default
                    save();
                });
            }

            // Event listener untuk floating tombol
            const buttonBar = document.getElementById('button-bar');
            const sentinel = document.getElementById('button-bar-sentinel'); // [ADDED]

            if (buttonBar && sentinel) {
                const observer = new IntersectionObserver(entries => {
                    const entry = entries[0];
                    if (!entry.isIntersecting) {
                        buttonBar.classList.add('floating');
                    } else {
                        buttonBar.classList.remove('floating');
                    }
                }, {
                    threshold: 0.1
                });

                observer.observe(sentinel);
            }

            // Event listener untuk navigasi ayat
            btnPrev.addEventListener('click', goToPrevVerse);
            btnNext.addEventListener('click', goToNextVerse);

            // Event listener untuk test button
            btnTestSave.addEventListener('click', saveOrUpdate);

            // =============================
            // INISIALISASI AWAL
            // =============================

            // Inisialisasi opsi ayat jika surah sudah dipilih
            if (surahOption.value) updateVerseCount();

            // Inisialisasi status tombol
            updatebtnMerge();
            updatebtnEditAndSplit();

        });
    </script>
@endpush
