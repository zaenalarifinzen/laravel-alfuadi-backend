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
                            <button class="btn btn-icon icon-left btn-primary" id="modal-add-word">
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
                                            <td class="text-center align-middle">
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

    <!-- Modal -->
    <form class="modal-part" id="modal-login-part">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputLafadz">Lafadz</label>
                    <input type="text" class="form-control arabic-text" id="inputLafadz" placeholder="لفظ">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputTranslation">Terjemah</label>
                    <input type="text" class="form-control" id="inputTranslation" placeholder="Terjemah">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputKalimah">Kalimah</label>
                    <select id="inputKalimah" class="form-control">
                        <option selected>اسم</option>
                        <option>فعل</option>
                        <option>حرف</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputHukum">Hukum</label>
                    <select id="inputHukum" class="form-control">
                        <option selected>مبني</option>
                        <option>معرب</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputMabniDetail">Mabni Detail</label>
                    <select id="inputMabniDetail" class="form-control">
                        <option selected>Fathah</option>
                        <option>Dhommah</option>
                        <option>Kasroh</option>
                        <option>Sukun</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputCategory">Kategori</label>
                    <select id="inputCategory" class="form-control">
                        <option selected>Pilih...</option>
                        <option>...</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputMahal">Kedudukan</label>
                    <select id="inputMahal" class="form-control">
                        <option selected>فاعل</option>
                        <option>مفعول</option>
                        <option>...</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputIrob">Irob</label>
                    <select id="inputIrob" class="form-control">
                        <option selected>رفع</option>
                        <option>نصب</option>
                        <option>جر</option>
                        <option>جزم</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputAlamat">Tanda I'rob</label>
                    <select id="inputAlamat" class="form-control">
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
                    <label for="inputCondition">Kondisi</label>
                    <select id="inputCondition" class="form-control">
                        <option selected>Fi Mahal</option>
                        <option>Dzohiroh</option>
                        <option>Muqoddaroh</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputMatbu">Yang Diikuti</label>
                    <input type="text" class="form-control arabic-text" id="inputMatbu" placeholder="لفظ">
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
    <script src="{{ asset('js/page/bootstrap-modal.js') }}"></script>

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

                if (verse_id) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace(':id', verse_id);
                } else if (surah_id && verse_number) {
                    url = "{{ route('wordgroups.get', ['id' => ':id']) }}".replace('/:id',
                        `?surah_id=${surah_id}&verse_number=${verse_number}`);
                } else {
                    alert('Parameter tidak lengkap')
                    return;
                }

                console.log(url);

                $.ajax({
                    url: url,
                    type: "GET",
                    // data: data,
                    success: function(response) {
                        console.log(`Hasil Fetch Word Group`);
                        console.log(response);

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

                        // Update URL di address bar
                        // const params = new URLSearchParams(data);
                        // history.pushState({}, '', `?${params.toString()}`);

                        currentSurahId.value = response.data.surah.id;
                        currentVerseNumber.value = response.data.verse.number;
                        currentVerseId.value = response.data.verse.id;
                        maxVerse = response.data.surah.verse_count;
                        surahOption.value = '';
                        verseOption.value = '';

                        currentVerseLabel.textContent =
                            `${response.data.surah.id}. ${response.data.surah.name} - Ayat ${response.data.verse.number}`;
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan');
                    }
                });
            }

            // =============================
            // FUNGSI FETCH WORDS
            // =============================

            function fetchWords(word_group_id) {
                // console.log(`Id WordGroup : ${word_group_id}`);
                const tbody = $("#sortable-table tbody");
                tbody.html(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <div class="spinner-border text-primary" role="status"></div>
                            <span class="ml-2">Memuat...</span>
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: "{{ route('words.get', ['id' => ':id']) }}".replace(':id', word_group_id),
                    type: "GET",
                    success: function(response) {
                        // console.log(response);

                        const tbody = $("#sortable-table tbody");
                        tbody.empty();

                        if (response.length === 0) {
                            tbody.append(`
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data kata.
                                    </td>
                                </tr>
                            `);
                            return;
                        }

                        response.data.forEach(function(word) {
                            let badgeClass = 'badge-light';
                            if (word.kalimat === 'فعل') badgeClass = 'badge-success';
                            else if (word.kalimat === 'اسم') badgeClass = 'badge-info';
                            else if (word.kalimat === 'حرف') badgeClass = 'badge-danger';

                            const row = `
                                <tr>
                                    <td class="text-center align-middle w-5">
                                        <div class="sort-handler align-middle">
                                            <i class="fa-solid fa-grip"></i>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle w-25">
                                        <div class="arabic-text words" id="${word.id}">${word.text}</div>
                                        <div class="table-links">
                                            <a href="#">Detail</a>
                                            <div class="bullet"></div>
                                            <a href="#">Edit</a>
                                            <div class="bullet"></div>
                                            <a href="#" class="text-danger">Hapus</a>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">${word.translation ?? ''}</td>
                                    <td class="text-center align-middle">
                                        <div class="badge ${badgeClass}">${word.kalimat ?? ''}</div>
                                    </td>
                                    <td class="arabic-text words">${word.kedudukan ?? ''}</td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
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
                    fetchWords(activeId);
                }
            });

            $slider.on('translated.owl.carousel', function(e) {
                const activeId = getActiveWgId(e);
                if (activeId) {
                    console.log('Slide translated, Active Id :', activeId);
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
