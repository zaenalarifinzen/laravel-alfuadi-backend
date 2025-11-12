@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">


    <style>
        #slider-rtl {
            direction: rtl;
        }

        #slider-rtl .owl-item .owl-stage-outer {
            direction: rtl;
            overflow: visible !important;
        }

        .td {
            align-items: center;
        }

        .arabic-text {
            font-size: 48px;
            line-height: 2.2;
            padding: 10px 0;
            display: block;
        }

        @media (max-width: 1080px) {
            .word-group {
                font-size: 1.5rem !important;
                text-align: center;
            }
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Input I'rob</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control {{-- select2 --}} form-control-sm" name="surah-option"
                                id="surah-option"
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
                <div class="card">
                    <input type="hidden" id="surah-id" value="{{ $surahId }}">
                    <input type="hidden" id="verse-number" value="{{ $verseNumber }}">
                    <input type="hidden" id="verse-id" value="{{ $verseId }}">

                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                                <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button>

                            <h4 id="current-verse-label">{{ $surahId }}. {{ $surahName }} - Ayat
                                {{ $verseNumber }}</h4>

                            <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                    class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button>
                        </div>
                    </div>

                    <div class="card-body d-flex justify-content-between align-items-center" style="gap: 1rem;">
                        <button id="btn-next-slide" class="btn btn-outline-primary btn-lg flex-shrink-0">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <div class="flex-grow-1" style="max-width: 80%; overflow: hidden;">
                            <div class="owl-carousel owl-theme slider" id="slider-rtl">
                                @foreach ($wordgroups as $wordgroup)
                                    <div>
                                        <h4 class="arabic-text word-group" wg-id="{{ $wordgroup->id }}">
                                            {{ $wordgroup->text }}
                                        </h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button id="btn-prev-slide" class="btn btn-outline-primary btn-lg flex-shrink-0">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h4 class="mb-0">Data Kalimah</h4>
                            <button class="btn btn-icon icon-left btn-primary" id="btn-add-word">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-striped table" id="sortable-table">
                                <thead>
                                    <tr class="text-center">
                                        <th>
                                            <i class="fa-solid fa-sort"></i>
                                        </th>
                                        <th>Lafadz</th>
                                        <th>Terjemah</th>
                                        <th>Kalimah</th>
                                        <th>Kedudukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($words as $word)
                                        <tr class="text-center">
                                            <td>
                                                <div class="sort-handler">
                                                    <i class="fa-solid fa-grip"></i>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle" id="{{ $word->id }}">
                                                <div class="arabic-text words">{{ $word->text }}</div>
                                                <div class="table-links">
                                                    <a href="#">Detail</a>
                                                    <div class="bullet"></div>
                                                    <a href="#">Edit</a>
                                                    <div class="bullet"></div>
                                                    <a href="#" class="text-danger">Hapus</a>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $word->translation }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge @if ($word->kalimat == 'فعل') badge-success
                                                @elseif($word->kalimat == 'اسم') badge-info
                                                @elseif($word->kalimat == 'حرف') badge-danger
                                                @else badge-light @endif">
                                                    {{ $word->kedudukan }}</div>
                                            </td>
                                            <td class="arabic-text words">
                                                {{ $word->jenis }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-icon icon-left btn-success"><i class="fa-solid fa-floppy-disk"></i>
                            Simpan</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Form -->
    <form id="form-add-word">
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-lafadz">Lafadz</label>
                    <input type="text" class="form-control arabic-text text-center" id="input-lafadz"
                        placeholder="لفظ">
                </div>
                <div class="form-group col-md-6">
                    <label for="input-translation">Terjemah</label>
                    <input type="text" class="form-control text-center" id="input-translation"
                        placeholder="Terjemah">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-kalimah">Kalimah</label>
                    <select id="input-kalimah" class="form-control">
                        <option selected>اسم</option>
                        <option>فعل</option>
                        <option>حرف</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="input-variation">Jenis</label>
                    <select id="input-variation" class="form-control">
                        <option selected>Pilih</option>
                        <option>...</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-hukum">Hukum</label>
                    <select id="input-hukum" class="form-control">
                        <option selected>مبني</option>
                        <option>معرب</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="input-mabni-detail">Detail Mabni</label>
                    <select id="input-mabni-detail" class="form-control">
                        <option selected>Fathah</option>
                        <option>Dhommah</option>
                        <option>Kasroh</option>
                        <option>Sukun</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-category">Kategori</label>
                    <select id="input-category" class="form-control">
                        <option selected>Pilih...</option>
                        <option>...</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="input-mahal">Kedudukan</label>
                    <select id="input-mahal" class="form-control">
                        <option selected>فاعل</option>
                        <option>مفعول</option>
                        <option>...</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-irob">Irob</label>
                    <select id="input-irob" class="form-control">
                        <option selected>رفع</option>
                        <option>نصب</option>
                        <option>جر</option>
                        <option>جزم</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="input-alamat">Tanda I'rob</label>
                    <select id="input-alamat" class="form-control">
                        <option selected>Fathah</option>
                        <option>Dhommah</option>
                        <option>Kasroh</option>
                        <option>Sukun</option>
                        <option>Wawu</option>
                        <option>Ya</option>
                        <option>Fi Mahal</option>
                        <option>...</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="input-condition">Kondisi</label>
                    <select id="input-condition" class="form-control">
                        <option selected>Fi Mahal</option>
                        <option>Dzohiroh</option>
                        <option>Muqoddaroh</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="input-matbu">Yang di ikuti</label>
                    <input type="text" class="form-control arabic-text" id="input-matbu" placeholder="لفظ">
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/words/word-crud.js') }}"></script>

    <script>
        const $slider = $("#slider-rtl");

        // Inisialisasi awal Owl Carousel
        $slider.owlCarousel({
            rtl: true,
            items: 1,
            dots: false,
            nav: false,
            loop: false,
            navText: [
                '<i class="fa fa-chevron-right"></i>',
                '<i class="fa fa-chevron-left"></i>'
            ]
        });

        $("#btn-next-slide").click(function() {
            $slider.trigger("next.owl.carousel");
        });

        $("#btn-prev-slide").click(function() {
            $slider.trigger("prev.owl.carousel");
        });

        document.addEventListener('DOMContentLoaded', function() {
            const surahOption = document.getElementById('surah-option');
            const verseOption = document.getElementById('verse-option');
            const filterForm = document.getElementById('filter-form');

            const btnPrev = document.getElementById('btn-prev-verse');
            const btnNext = document.getElementById('btn-next-verse');

            const currentVerseLabel = document.getElementById('current-verse-label');

            const currentSurahId = document.getElementById('surah-id');
            const currentVerseNumber = document.getElementById('verse-number');
            const currentVerseId = document.getElementById('verse-id');

            let activeWordGroupId;
            let modified = false;
            let verseCount;

            function updateVerseCount() {
                const selected = surahOption.options[surahOption.selectedIndex];
                verseCount = selected ? selected.getAttribute('data-verse-count') : 0;
                console.log(`Jumlah Ayat: ${verseCount}`);

            }

            async function handleFilterSubmit(e) {
                e.preventDefault();

                // if (modified) {
                //     const confirmed = await showEditConfirmation()
                //     if (!confirmed) return;
                // };

                fetchWordGroups(surahOption.value, verseOption.value);
                // modified = false;
                // console.log(`Surah Id = ${surahOption.value} Verse = ${verseOption.value}`)
            }

            // =============================
            // FUNGSI FETCH WORDGROUPS
            // =============================

            function fetchWordGroups(surah_id, verse_number, verse_id) {
                let url;

                // create API
                if (verse_id) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace(':id', verse_id);
                } else if (surah_id && verse_number) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace('/:id',
                        `?surah_id=${surah_id}&verse_number=${verse_number}`);
                } else {
                    alert('Parameter tidak lengkap')
                    return;
                }

                // Fetch Data
                $.ajax({
                    url: url,
                    type: "GET",
                    // data: data,
                    success: function(response) {
                        console.log('Hasil Fetch Word Group', response);
                        const verseId = response.data.verse.id;
                        const storageKey = `wordgroups_${verseId}`;

                        // local storage check
                        // const cachedData = localStorage.getItem(storageKey);
                        // if (cachedData) {
                        //     console.log('Data diambil dari local storage');
                        //     const response = JSON.parse(cachedData);
                        //     renderWordGroups(response);
                        //     return;
                        // }

                        // clear cached wordgroups
                        for (let key in localStorage) {
                            if (key.startsWith("wordgroups_")) {
                                localStorage.removeItem(key);
                            }
                        }

                        // save to local storage
                        localStorage.setItem(storageKey, JSON.stringify(response))

                        renderWordGroups(response);

                        // Update URL di address bar
                        // const params = new URLSearchParams(data);
                        // history.pushState({}, '', `?${params.toString()}`);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan');
                    }
                });
            }

            function renderWordGroups(response) {
                $slider.trigger('destroy.owl.carousel');
                $slider.html('');

                $.each(response.data.wordGroups, function(i, wordGroup) {
                    $slider.append(`
                                <div>
                                    <h4 class="arabic-text word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>
                                </div>
                            `);
                });

                $slider.owlCarousel({
                    rtl: true,
                    items: 1,
                    dots: false,
                    nav: false,
                    navText: [
                        '<i class="fa fa-chevron-right"></i>',
                        '<i class="fa fa-chevron-left"></i>'
                    ]
                });

                currentSurahId.value = response.data.surah.id;
                currentVerseNumber.value = response.data.verse.number;
                currentVerseId.value = response.data.verse.id;
                maxVerse = response.data.surah.verse_count;
                surahOption.value = '';
                verseOption.value = '';

                currentVerseLabel.textContent =
                    `${response.data.surah.id}. ${response.data.surah.name} - Ayat ${response.data.verse.number}`;
            }

            // =============================
            // FUNGSI FETCH WORDS
            // =============================

            function fetchWords(word_group_id) {
                const tbody = $("#sortable-table tbody");
                tbody.html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <div class="spinner-border text-primary" role="status"></div>
                            <span class="ml-2">Memuat...</span>
                        </td>
                    </tr>
                `);

                const allKey = Object.keys(localStorage).filter(k => k.startsWith('wordgroups_'));
                if (allKey.length === 0) {
                    tbody.html(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada data.
                            </td>
                        </tr>
                    `);
                    return;
                }

                // get wordgroup object from local storage
                const stored = JSON.parse(localStorage.getItem(allKey[0]));
                const wordGroups = stored.data.wordGroups || [];

                // check active wordgroup
                const activeWordGroup = wordGroups.find(wg => wg.id == word_group_id);

                tbody.empty();

                if (!activeWordGroup || !activeWordGroup.words || activeWordGroup.words.length === 0) {
                    tbody.append(`
                         <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada data.
                            </td>
                        </tr>                   
                    `);
                    return;
                }

                // render all words
                renderWordsTable(activeWordGroup);
            }

            $(document).ready(function() {
                const $slider = $("#slider-rtl").owlCarousel({
                    rtl: true,
                    items: 5,
                    margin: 10,
                    nav: false,
                    dots: false,
                });

                // Ambil id wordgroup pertama yang aktif saat halaman pertama kali dimuat
                const firstWgId = $("#slider-rtl .owl-item.active .word-group").attr("wg-id");
                if (firstWgId) {
                    fetchWords(firstWgId);
                }
            });

            function getActiveWgId(event) {
                let $active = $slider.find('.owl-item.active').first();
                let id = $active.find('.word-group').attr('wg-id');

                if (id) return id;

                try {
                    const $items = $slider.find('.owl-item').not('.cloned');
                    if (event && event.item && typeof event.item.index === 'number' && $items.length) {
                        let idx = event.item.index;

                        // normalize index ke range 0..length-1
                        idx = ((idx % $items.length) + $items.length) % $items.length;
                        id = $items.eq(idx).find('.word-group').attr('wg-id');
                        return id;
                    }
                } catch (err) {
                    console.error(err);
                }
                return null;
            }

            $slider.on('initialized.owl.carousel', function(e) {
                const activeId = getActiveWgId(e);
                if (activeId) {
                    console.log(`Initial Active Id : ${activeId}`);
                    activeWordGroupId = activeId;
                    fetchWords(activeId);
                }
            });

            $slider.on('translated.owl.carousel', function(e) {
                const activeId = getActiveWgId(e);
                if (activeId) {
                    console.log('Slide translated, Active Id :', activeId);
                    activeWordGroupId = activeId;
                    fetchWords(activeId);
                } else {
                    console.log('No activeId found after translate');
                }
            });

            // =============================
            // FUNGSI NAVIGASI AYAT
            // =============================

            async function goToPrevVerse() {
                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                let verseId = parseInt(currentVerseId.value);
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

                let verseId = parseInt(currentVerseId.value) || 1;
                const max = 6236;

                if (verseId < max) {
                    currentVerseId.value = verseId + 1;
                    fetchWordGroups(null, null, currentVerseId.value);
                    modified = false;
                }
            }

            filterForm.addEventListener('submit', handleFilterSubmit);

            // Event listener untuk perubahan surah
            surahOption.addEventListener('change', function() {
                updateVerseCount();
                verseOption.value = 1;
            });

            // Event listener untuk input nomor ayat
            verseOption.addEventListener('change', function() {
                if (parseInt(verseOption.value) > verseCount) {
                    verseOption.value = verseCount;
                }
            });

            // Inisialisasi opsi ayat jika surah sudah dipilih
            if (surahOption.value) updateVerseOptions();

            // Event listener untuk navigasi ayat
            btnPrev.addEventListener('click', goToPrevVerse);
            btnNext.addEventListener('click', goToNextVerse);
        });
    </script>
@endpush
