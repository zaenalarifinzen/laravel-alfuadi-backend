/* ==========================================================================
   File: words-create.js
   Description: Handles I'rob Input Page (Words)
   Dependencies: jQuery, OwlCarousel, Bootstrap Modal
   ========================================================================== */

$(function () {
    const $slider = $("#slider-rtl");

    // =============================
    // 1. INIT OWL CAROUSEL
    // =============================
    $slider.owlCarousel({
        rtl: true,
        items: 1,
        dots: false,
        nav: false,
        loop: false,
    });

    $("#btn-next-slide").on("click", () => $slider.trigger("next.owl.carousel"));
    $("#btn-prev-slide").on("click", () => $slider.trigger("prev.owl.carousel"));

    // =============================
    // 2. ELEMENTS
    // =============================
    const surahOption = document.getElementById("surah-option");
    const verseOption = document.getElementById("verse-option");
    const filterForm = document.getElementById("filter-form");

    const btnPrev = document.getElementById("btn-prev-verse");
    const btnNext = document.getElementById("btn-next-verse");

    const currentVerseLabel = document.getElementById("current-verse-label");
    const currentSurahId = document.getElementById("surah-id");
    const currentVerseNumber = document.getElementById("verse-number");
    const currentVerseId = document.getElementById("verse-id");

    let modified = false;
    let verseCount = 0;

    // =============================
    // 3. HELPER FUNCTIONS
    // =============================

    function updateVerseCount() {
        const selected = surahOption.options[surahOption.selectedIndex];
        verseCount = selected ? selected.getAttribute("data-verse-count") : 0;
        console.log(`Jumlah Ayat: ${verseCount}`);
    }

    function getActiveWgId(event) {
        let $active = $slider.find(".owl-item.active").first();
        let id = $active.find(".word-group").attr("wg-id");

        if (id) return id;

        try {
            const $items = $slider.find(".owl-item").not(".cloned");
            if (event?.item && typeof event.item.index === "number" && $items.length) {
                let idx = event.item.index;
                idx = ((idx % $items.length) + $items.length) % $items.length;
                id = $items.eq(idx).find(".word-group").attr("wg-id");
                return id;
            }
        } catch (err) {
            console.error(err);
        }
        return null;
    }

    // =============================
    // 4. FETCH FUNCTIONS
    // =============================

    function fetchWordGroups(surah_id, verse_number, verse_id) {
        const data = {};
        if (surah_id) data.surah_id = surah_id;
        if (verse_number) data.verse_number = verse_number;
        if (verse_id) data.verse_id = verse_id;

        $.ajax({
            url: "{{ route('wordgroups.index') }}", // Laravel route helper (or replace with hardcoded if needed)
            type: "GET",
            data,
            success(response) {
                $slider.trigger("destroy.owl.carousel").html("");

                response.wordgroups.forEach((wordgroup) => {
                    $slider.append(`
                        <div>
                            <h4 class="arabic-text word-group" wg-id="${wordgroup.id}">
                                ${wordgroup.text}
                            </h4>
                        </div>
                    `);
                });

                $slider.owlCarousel({
                    rtl: true,
                    items: 1,
                    dots: false,
                    nav: false,
                });

                const params = new URLSearchParams(data);
                history.pushState({}, "", `?${params.toString()}`);

                currentSurahId.value = response.surah.id;
                currentVerseNumber.value = response.verse.number;
                currentVerseId.value = response.verse.id;

                currentVerseLabel.textContent = `${response.surah.id}. ${response.surah.name} - Ayat ${response.verse.number}`;
            },
            error(xhr) {
                console.error(xhr.responseText);
            },
        });
    }

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

        $.ajax({
            url: "{{ route('words.index') }}",
            type: "GET",
            data: { word_group_id },
            success(response) {
                tbody.empty();

                if (!response.length) {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data kata.</td>
                        </tr>
                    `);
                    return;
                }

                response.forEach((word) => {
                    let badgeClass = "badge-light";
                    if (word.kalimat === "فعل") badgeClass = "badge-success";
                    else if (word.kalimat === "اسم") badgeClass = "badge-info";
                    else if (word.kalimat === "حرف") badgeClass = "badge-danger";

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
                            <td class="text-center align-middle">${word.translation ?? ""}</td>
                            <td class="text-center align-middle">
                                <div class="badge ${badgeClass}">${word.kalimat ?? ""}</div>
                            </td>
                            <td class="arabic-text words">${word.kedudukan ?? ""}</td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error(xhr) {
                console.error(xhr.responseText);
            },
        });
    }

    // =============================
    // 5. EVENT HANDLERS
    // =============================

    filterForm.addEventListener("submit", function (e) {
        e.preventDefault();
        fetchWordGroups(surahOption.value, verseOption.value);
    });

    surahOption.addEventListener("change", function () {
        updateVerseCount();
        verseOption.value = 1;
    });

    verseOption.addEventListener("change", function () {
        if (parseInt(verseOption.value) > verseCount) {
            verseOption.value = verseCount;
        }
    });

    btnPrev.addEventListener("click", function () {
        const verseId = parseInt(currentVerseId.value);
        if (verseId > 1) {
            currentVerseId.value = verseId - 1;
            fetchWordGroups(null, null, currentVerseId.value);
        }
    });

    btnNext.addEventListener("click", function () {
        const verseId = parseInt(currentVerseId.value) || 1;
        const max = 6236;
        if (verseId < max) {
            currentVerseId.value = verseId + 1;
            fetchWordGroups(null, null, currentVerseId.value);
        }
    });

    // =============================
    // 6. INITIAL LOAD
    // =============================
    $(document).ready(() => {
        const firstWgId = $("#slider-rtl .owl-item.active .word-group").attr("wg-id");
        if (firstWgId) fetchWords(firstWgId);
    });

    $slider.on("initialized.owl.carousel", (e) => {
        const activeId = getActiveWgId(e);
        if (activeId) fetchWords(activeId);
    });

    $slider.on("translated.owl.carousel", (e) => {
        const activeId = getActiveWgId(e);
        if (activeId) fetchWords(activeId);
    });
});
