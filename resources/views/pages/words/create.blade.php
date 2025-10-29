@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">

    <style>
        #slider-rtl {
            direction: rtl;
        }

        #slider-rtl .owl-item {
            direction: rtl;
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Input I'rob</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.indexByVerse') }}" id="filter-form" class="mb-0">
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

                            <select class="form-control {{-- select2 --}} form-control-sm" name="verse-option"
                                id="verse-option" style="flex: 2;" required>
                                <option value="">Ayat</option>
                            </select>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center w-100">
                            <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                    class="ion-chevron-left" data-pack="default" data-tags="arrow, left"></i></button>
                            <h4 id="current-verse-label">Grup Kalimah</h4>
                            <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                                <i class="ion-chevron-right" data-pack="default" data-tags="arrow, right"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <innput type="hidden" id="verse-number" value="1">
                            <div class="owl-carousel owl-theme slider" id="slider-rtl">
                                @foreach ($wordgroups as $wordgroup)
                                    <div>
                                        <h4 class="arabic-text word-group">{{ $wordgroup->text }}</h4>
                                    </div>
                                @endforeach
                            </div>
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
                                    <tr>
                                        <th class="text-center">
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
                                        <tr>
                                            <td>
                                                <div class="sort-handler">
                                                    <i class="fa-solid fa-grip"></i>
                                                </div>
                                            </td>
                                            <td>
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
                                                    {{ $word->kalimat }}</div>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('js/page/modules-slider.js') }}"></script>
    <script src="{{ asset('js/page/bootstrap-modal.js') }}"></script>

    <script>
        const $slider = $("#slider-rtl");

        // Inisialisasi awal Owl Carousel
        $slider.owlCarousel({
            rtl: true,
            items: 1,
            dots: false,
            nav: true,
            navText: [
                '<i class="fa fa-chevron-right"></i>',
                '<i class="fa fa-chevron-left"></i>'
            ]
        });

        document.addEventListener('DOMContentLoaded', function() {
            const surahOption = document.getElementById('surah-option');
            const verseOption = document.getElementById('verse-option');
            const filterForm = document.getElementById('filter-form');

            const btnPrev = document.getElementById('btn-prev-verse');
            const btnNext = document.getElementById('btn-next-verse');

            const currentVerseLabel = document.getElementById('current-verse-label');
            const currentVerseNumber = document.getElementById('verse-number');
            let modified = false;

            function updateVerseOptions() {
                const selected = surahOption.options[surahOption.selectedIndex];
                const verseCount = selected ? selected.getAttribute('data-verse-count') : 0;
                const currentVerse = "{{ request('verse_number') }}";
                for (let i = 1; i <= verseCount; i++) {
                    verseOption.innerHTML +=
                        `<option value="${i}">${i}</option>`;
                }

                if (surahOption.value) {
                    verseOption.value = 1;
                } else {
                    verseOption.innerHTML = '<option value="">Ayat</option>';
                }
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

            function fetchWordGroups(surah_id, verse_number) {
                $.ajax({
                    url: "{{ route('wordgroups.index') }}",
                    type: "GET",
                    data: {
                        surah_id: surah_id,
                        verse_number: verse_number,
                    },
                    success: function(response) {
                        // console.log(response.data);
                        $slider.trigger('destroy.owl.carousel');
                        $slider.html('');

                        $.each(response.data, function(i, wordgroup) {
                            $slider.append(`
                                <div>
                                    <h4 class="arabic-text word-group">${wordgroup.text}</h4>
                                </div>
                            `);
                        });

                        $slider.owlCarousel({
                            rtl: true,
                            items: 1,
                            dots: false,
                            nav: true,
                            navText: [
                                '<i class="fa fa-chevron-right"></i>',
                                '<i class="fa fa-chevron-left"></i>'
                            ]
                        });

                        currentVerseLabel.textContent = `{surahName} - Ayat ${verse_number}`;
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                })
            }

            // =============================
            // FUNGSI NAVIGASI AYAT
            // =============================

            async function goToPrevVerse() {
                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                verseNumber = parseInt(currentVerseNumber.value) || 5;
                console.log(verseNumber);

                if (verseNumber > 1) {
                    currentVerseNumber.value = verseNumber - 1;
                    fetchWordGroups(1, verseNumber);
                    modified = false;
                }
            }

            async function goToNextVerse() {
                if (modified) {
                    const confirmed = await showEditConfirmation()
                    if (!confirmed) return;
                };

                let verseNumber = parseInt(currentVerseNumber.value);
                const max = maxVerse;

                // console.log(`Max: ${max}`);

                if (verseNumber < max) {
                    currentVerseNumber.value = verseNumber + 1;
                    fetchVerse(currentSurahId.value, currentVerseNumber.value);
                    modified = false;
                }
            }

            filterForm.addEventListener('submit', handleFilterSubmit);

            // Event listener untuk perubahan surah
            surahOption.addEventListener('change', function() {
                updateVerseOptions();
                verseOption.selectedIndex = 0;
            });

            // Inisialisasi opsi ayat jika surah sudah dipilih
            if (surahOption.value) updateVerseOptions();

            // Event listener untuk navigasi ayat
            btnPrev.addEventListener('click', goToPrevVerse);
            btnNext.addEventListener('click', goToNextVerse);
        });
    </script>
@endpush
