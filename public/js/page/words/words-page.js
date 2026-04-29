/* ==========================================================================
   File: griuping.js
   Description: Handles I'rob Input Page (Words)
   Dependencies: jQuery, OwlCarousel, Bootstrap Modal
   ========================================================================== */

// =============================
// CONSTANTS
// =============================
const WORDS_SYNC_URL = window.WORDS_SYNC_URL;
const CSRF_TOKEN = window.CSRF_TOKEN;

// =============================
// OWL CAROUSEL INIT
// =============================
const $slider = $("#slider-rtl").owlCarousel({
    rtl: true,
    items: 1,
    dots: false,
    nav: false,
    loop: false,
});

$("#btn-next-slide").click(() => $slider.trigger("next.owl.carousel"));
$("#btn-prev-slide").click(() => $slider.trigger("prev.owl.carousel"));

// =============================
// GLOBAL ELEMENTS
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

let activeWordGroupId = null;
let verseCount = 0;
const wordGroupsPrefix = "wordgroups_";

// =============================
// HELPERS
// =============================
function updateVerseCount() {
    const selected = surahOption.options[surahOption.selectedIndex];
    verseCount = selected ? selected.getAttribute("data-verse-count") : 0;
}

function showEditConfirmation() {
    return swal({
        icon: "warning",
        title: "Perubahan belum disimpan",
        text: "Abaikan perubahan yang sudah ada?",
        buttons: {
            cancel: {
                text: "Kembali",
                visible: true,
            },
            confirm: {
                text: "Abaikan",
                visible: true,
                className: "btn-success",
            },
        },
    });
}

function showLoading() {
    $("#loading-overlay").css({
        visibility: "visible",
        opacity: "1",
    });
}

function hideLoading() {
    $("#loading-overlay").css({
        visibility: "hidden",
        opacity: "0",
    });
}

// =============================
// FETCH SURAH LIST
// =============================
function fetchSurahList() {
    $.ajax({
        url: '/api/surahs',
        type: 'GET',
        success: function (response) {
            response.data.forEach((surah) => {
                surahOption.insertAdjacentHTML('beforeend',
                    `<option value="${surah.id}" data-verse-count="${surah.verse_count}">${surah.id}. ${surah.name}</option>`
                );
            });
        }
    });    
}

// =============================
// FETCH WORDGROUPS
// =============================
function fetchWordGroups(surah_id, verse_number, verse_id) {
    let url;

    if (verse_id) {
        url = WORDGROUP_GET_URL.replace(":id", verse_id);
    } else if (surah_id && verse_number) {
        url = WORDGROUP_GET_URL.replace(
            "/:id",
            `?surah_id=${surah_id}&verse_number=${verse_number}`,
        );
    } else {
        alert("Parameter tidak lengkap");
        return;
    }

    $.ajax({
        url,
        type: "GET",
        success: function (response) {            
            const verseId = response.data.verse.id;
            const storageKey = `wordgroups_${verseId}`;

            response.modified = false;
            removeRefreshButton();

            // Clear old cache
            Object.keys(localStorage)
                .filter((k) => k.startsWith("wordgroups_"))
                .forEach((k) => localStorage.removeItem(k));

            localStorage.setItem(storageKey, JSON.stringify(response));
            renderWordGroups(response);

            // Update URL in address bar
            history.pushState({}, "", `?verse_id=${verseId}`);
        },
        error: function () {
            alert("Terjadi kesalahan");
        },
    });
}

// =============================
// RENDER WORDGROUPS SLIDER
// =============================
function renderWordGroups(response) {
    $slider.trigger("destroy.owl.carousel");
    $slider.html("");

    response.data.wordGroups.forEach((wordGroup) => {
        $slider.append(`
      <div>
        <h4 class="arabic-text ar-title word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>
      </div>`);
    });

    $slider.owlCarousel({ rtl: true, items: 1, dots: false, nav: false });

    currentSurahId.value = response.data.surah.id;
    currentVerseNumber.value = response.data.verse.number;
    currentVerseId.value = response.data.verse.id;

    surahOption.value = "";
    verseOption.value = "";

    currentVerseLabel.textContent = `${response.data.surah.id}. ${response.data.surah.name} - Ayat ${response.data.verse.number}`;

    // update wordgroup editor info
    const firstWordGroup = response.data.wordGroups[0];
    const btnAddWord = document.getElementById("btn-add-word");

    if (firstWordGroup.editor_info) {
        const editorName = firstWordGroup.editor_info.name;
        $(".editor-wordgroup a").contents().last()[0].textContent =
            ` ${editorName}`;
        btnAddWord.style.display = "inline-block";
    } else {
        $(".editor-wordgroup a").contents().last()[0].textContent = ` -`;
        btnAddWord.style.display = "none";

        swal({
            title: "Ayat belum melalui proses grouping",
            text: "Silakan lakukan grouping terlebih dahulu untuk dapat menambahkan kalimat",
            icon: "info",
            buttons: {
                cancel: {
                    text: "Tutup",
                    visible: true,
                },
                confirm: {
                    text: "Grouping",
                    visible: true,
                },
            },
        }).then((confirmed) => {
            if (!confirmed) return;

            // redirect to grouping page
            const groupingUrl = `/wordgroups/grouping?verse_id=${response.data.verse.id}`;
            window.location.href = groupingUrl;
        });
    }
}

// =============================
// RENDER WORDS TABLE
// =============================
function renderWordsTable(wordGroup) {
    const tbody = $("#sortable-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);

        $(".editor-kalimat a").contents().last()[0].textContent = ` -`;
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0),
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const row = `
            <tr>
                <td class="text-center align-middle col-word">
                    <div class="${simbolClass} arabic-text words" id="${word.id}">${word.text}</div>
                </td>
                <td class="text-center align-middle col-symbol">
                    <div class="text-center ${simbolClass} mb-2 arabic-text ar-symbol">${
                        word.simbol ?? ""
                    }</div>
                </td>
                <td class="align-middle col-translation">${word.translation ?? ""}</td>
                <td class="align-middle col-action">
                    <div class="d-flex justify-content-center action-buttons">
                        <button class="btn btn-sm btn-icon btn-warning word-edit" id="btn-edit" title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-danger word-delete" id="btn-delete" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-primary btn-move-up" title="Naikkan">
                            <i class="fa-solid fa-arrow-up"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-primary btn-move-down" title="Turunkan">
                            <i class="fa-solid fa-arrow-down"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    const firstWord = wordGroup.words[0];
    const editorName = firstWord.editor_info
        ? firstWord.editor_info.name
        : " -";
    $(".editor-kalimat a").contents().last()[0].textContent = ` ${editorName}`;

    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        $("#btn-save-all").show();
    } else {
        $("#btn-save-all").hide();
    }
}

// =============================
// RENDER WORDS DETAILS
// =============================
function renderWordsDetails(wordGroup) {
    const tbody = $("#detail-kalimat-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        console.log("Data tidak tersedia");
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0),
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const parts = [
            word.kalimat,
            word.hukum,
            word.kategori,
            word.kedudukan,
            word.irob,
            word.tanda,
        ]
            .filter(
                (p) => p !== null && p !== undefined && String(p).trim() !== "",
            )
            .join(" - ");

        const row = `
            <tr class="text-center kalimat-detail-row">
                <td>
                    <div class="text-right arabic-text ar-subtitle">
                        ${parts}
                    </div>
                </td>
                <td class="text-center align-middle word" id="${word.id}">
                       <div class="${simbolClass} arabic-text words">
                           ${word.text}
                       </div>
                          <div class="text-center ${simbolClass} arabic-text ar-symbol-mini">
                            ${word.simbol ?? ""}
                        </div>
                       <div class="translation">
                           ${word.translation ?? ""}
                       </div>
                   </td>
             </tr>
             
        `;
        tbody.append(row);
    });
}

// =============================
// FETCH WORDS
// =============================
function fetchWords(word_group_id) {
    const tbodyWords = $("#sortable-table tbody");
    const tbodyWordsDetail = $("#detail-kalimat-table tbody");

    const key = Object.keys(localStorage).find((k) =>
        k.startsWith("wordgroups_"),
    );
    if (!key) {
        const row = `
            <tr>
                <div class="spinner-border text-primary" role="status"></div>
                <span class="ml-2">Memuat...</span>
            </tr>
        `;
    }

    const stored = JSON.parse(localStorage.getItem(key));
    const activeGroup = stored.data.wordGroups.find(
        (wg) => wg.id == word_group_id,
    );

    if (!activeGroup || !activeGroup.words || activeGroup.words.length === 0) {
        const row = `
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `;

        $(".editor-kalimat a").contents().last()[0].textContent = " -";

        tbodyWords.html(row);
        tbodyWordsDetail.html(row);
        return;
    }

    renderWordsTable(activeGroup);
    renderWordsDetails(activeGroup);
}

// =============================
// GET ACTIVE OWL ITEM
// =============================
function getActiveWgId(event) {
    const $active = $slider.find(".owl-item.active").first();
    const id = $active.find(".word-group").attr("wg-id");
    return id || null;
}

$slider.on("initialized.owl.carousel", (e) => {
    const id = getActiveWgId(e);
    if (id) fetchWords(id);
});

$slider.on("translated.owl.carousel", (e) => {
    const id = getActiveWgId(e);
    if (id) fetchWords(id);
});

// =============================
// NAVIGASI AYAT
// =============================
async function goToPrevVerse() {
    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    let id = parseInt(currentVerseId.value);
    if (id > 1) fetchWordGroups(null, null, id - 1);
}

async function goToNextVerse() {
    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    let id = parseInt(currentVerseId.value);
    if (id < 6236) fetchWordGroups(null, null, id + 1);
}

function addRefreshButton() {
    const cardHeader = document.getElementById("word");

    // buat tombol refresh
    const wrapper = document.createElement("div");
    wrapper.innerHTML = `
        <button class="btn btn-icon icon-left btn-info btn-lg" id="btn-reload-wordgroups">
            <i class="fa-solid fa-rotate"></i> Refresh
        </button>
    `;

    const refreshBtn = wrapper.querySelector("#btn-reload-wordgroups");

    // tambahkan event listener
    refreshBtn.addEventListener("click", async function (e) {
        e.preventDefault();

        const confirmed = await showEditConfirmation();
        if (!confirmed) return;

        fetchWordGroups(null, null, currentVerseId.value);
    });

    const headerContainer = cardHeader.querySelector(".d-flex");
    if (headerContainer) {
        headerContainer.appendChild(wrapper);
    } else {
        cardHeader.appendChild(refreshBtn);
    }
}

function removeRefreshButton() {
    const btn = document.getElementById("btn-reload-wordgroups");

    if (btn) {
        btn.remove();
    }
}

// =============================
// EVENT LISTENERS
// =============================
async function searchVerse(e) {
    e.preventDefault();

    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    fetchWordGroups(surahOption.value, verseOption.value);
}

surahOption.addEventListener("change", () => {
    updateVerseCount();
    verseOption.value = 1;
});

verseOption.addEventListener("change", () => {
    if (parseInt(verseOption.value) < 1) {
        verseOption.value = 1;
    }
    if (parseInt(verseOption.value) > verseCount)
        verseOption.value = verseCount;
});

filterForm.addEventListener("submit", searchVerse);
btnPrev.addEventListener("click", goToPrevVerse);
btnNext.addEventListener("click", goToNextVerse);

// =============================
// DOM
// =============================
document.addEventListener("DOMContentLoaded", () => {
    fetchSurahList();

    const initialVerseId = currentVerseId?.value;

    if (!initialVerseId) {
        console.warn("No initial verse ID found");
        return;
    }

    const cachedKey = getActiveStorageKey(wordGroupsPrefix);
    const currentKey = `wordgroups_${initialVerseId}`;
    const cached = localStorage.getItem(cachedKey);

    // ------------------------------------------------------
    // 1. Jika TIDAK ADA cache sama sekali → fetch baru
    // ------------------------------------------------------
    if (!cached) {
        fetchWordGroups(null, null, initialVerseId);
        return;
    }

    // ------------------------------------------------------
    // 2. Jika ADA cache, tapi berbeda ayat → tampilkan modal restore
    // ------------------------------------------------------
    if (cachedKey !== currentKey) {
        const data = JSON.parse(cached);

        const lastProgressLabel = `${data.data.surah.name} - Ayat ${data.data.verse.number}`;

        const restoreUrl = `/words/create?verse_id=${data.data.verse.id}`;

        $("#last-location-label").text(lastProgressLabel);

        $("#btn-restore-continue")
            .off("click")
            .on("click", () => (window.location.href = restoreUrl));

        $("#btn-restore-cancel")
            .off("click")
            .on("click", () => {
                $("#modal-restore").modal("hide");
                fetchWordGroups(null, null, initialVerseId);
            });

        $("#modal-restore").modal("show");
        return;
    }

    // ------------------------------------------------------
    // 3. Jika ADA cache dan sesuai ayat → restore langsung
    // ------------------------------------------------------
    const data = JSON.parse(cached);
    renderWordGroups(data);
    if (data.modified) {
        // add refresh button in card header
        addRefreshButton();

        iziToast.info({
            message: "Data sebelumnya berhasil dipulihkan",
            position: "bottomCenter",
        });
    }

    const firstGroup = data.data.wordGroups?.[0];
    if (firstGroup) fetchWords(firstGroup.id);
});

$(document).ajaxStart(showLoading);
$(document).ajaxStop(hideLoading);
