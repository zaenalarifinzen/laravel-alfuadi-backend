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
const wordGroupsPrefix =
    window.PAGE_TYPE === "exercise" ? "answer_user_" : "wordgroups_";

let currentCompareResult = [];
let currentCompareVerseId = null;

// =============================
// ANSWER COMPARISON & VALIDATION
// =============================
function compareAnswers(verseId) {
    const answerKeyRaw = localStorage.getItem(`answer_key_${verseId}`);
    const answerUserRaw = localStorage.getItem(`answer_user_${verseId}`);

    if (!answerKeyRaw || !answerUserRaw) {
        console.warn("Answer key atau user answer tidak ditemukan");
        return [];
    }

    const answerKey = JSON.parse(answerKeyRaw);
    const answerUser = JSON.parse(answerUserRaw);

    const fields = [
        "kalimat",
        "hukum",
        "kategori",
        "kedudukan",
        "irob",
        "tanda",
        "simbol",
    ];
    const result = [];

    answerKey.wordGroups.forEach((keyGroup) => {
        const userGroup = answerUser.wordGroups.find(
            (g) => g.id === keyGroup.id,
        );
        if (!userGroup) return;

        keyGroup.words.forEach((keyWord) => {
            const userWord = userGroup.words.find((w) => w.id === keyWord.id);
            if (!userWord) return;

            const fieldsResult = fields.map((field) => {
                const expected = String(keyWord[field] ?? "").trim();
                const actual = String(userWord[field] ?? "").trim();
                return {
                    field,
                    expected,
                    actual,
                    correct: expected === actual,
                };
            });

            result.push({
                wordId: keyWord.id,
                text: keyWord.text,
                correct: fieldsResult.every((f) => f.correct),
                fields: fieldsResult,
            });
        });
    });

    return result;
}

function clearComparisonHighlights() {
    document.querySelectorAll("#sortable-table tbody tr").forEach((tr) => {
        tr.classList.remove("is-wrong", "is-correct");
        tr.querySelectorAll("td.is-wrong").forEach((td) => {
            td.classList.remove("is-wrong");
        });
    });
}

function applyComparisonHighlights() {
    if (!currentCompareResult || currentCompareResult.length === 0) return;
    highlightErrors(currentCompareResult);
}

function highlightErrors(compareResult) {
    clearComparisonHighlights();

    compareResult.forEach((item) => {
        const row = document.querySelector(
            `#sortable-table tbody tr div.words[id="${item.wordId}"]`,
        );

        if (!row) return;

        const tr = row.closest("tr");
        if (!tr) return;

        if (item.correct) {
            tr.classList.remove("is-wrong");
            tr.classList.add("is-correct");
        } else {
            tr.classList.remove("is-correct");
            tr.classList.add("is-wrong");

            item.fields.forEach((fieldResult) => {
                if (!fieldResult.correct) {
                    let colClass;
                    switch (fieldResult.field) {
                        case "kalimat":
                            colClass = ".col-kalimat";
                            break;
                        case "hukum":
                            colClass = ".col-hukum";
                            break;
                        case "kategori":
                            colClass = ".col-kategori";
                            break;
                        case "kedudukan":
                            colClass = ".col-kedudukan";
                            break;
                        case "irob":
                            colClass = ".col-irob";
                            break;
                        case "tanda":
                            colClass = ".col-tanda";
                            break;
                        default:
                            colClass = null;
                    }

                    if (colClass) {
                        const td = tr.querySelector(colClass);
                        if (td) td.classList.add("is-wrong");
                    }
                }
            });
        }
    });
}

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
        url: "/api/surahs",
        type: "GET",
        success: function (response) {
            response.data.forEach((surah) => {
                surahOption.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${surah.id}" data-verse-count="${surah.verse_count}">${surah.id}. ${surah.name}</option>`,
                );
            });
        },
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
            const data = response.data;
            const verseId = data.verse.id;
            const storageKey = `${wordGroupsPrefix}${verseId}`;

            data.modified = false;
            removeRefreshButton();

            // if exercise page
            if (window.PAGE_TYPE === "exercise") {
                // Clear old answer cache
                currentCompareResult = [];
                currentCompareVerseId = null;
                Object.keys(localStorage)
                    .filter(
                        (k) =>
                            k.startsWith("answer_key_") ||
                            k.startsWith("answer_user_"),
                    )
                    .forEach((k) => localStorage.removeItem(k));

                // preserve to answer
                const cloned = structuredClone(data);
                
                const answerKey = `answer_key_${verseId}`;
                const userAnswer = `answer_user_${verseId}`;

                localStorage.setItem(answerKey, JSON.stringify(cloned));

                // Validasi before iteraion
                if (cloned.wordGroups && Array.isArray(cloned.wordGroups) && cloned.wordGroups.length > 0) {
                    cloned.wordGroups.forEach((wg) => {
                        if (wg && wg.words && Array.isArray(wg.words)) {
                            wg.words.forEach((w) => {
                                w.color = null;
                                w.kalimat = null;
                                w.hukum = null;
                                w.kategori = null;
                                w.kedudukan = null;
                                w.irob = null;
                                w.tanda = null;
                            });
                        }
                    });
                } else {
                    console.warn("Data wordGroups tidak valid atau kosong");
                }

                localStorage.setItem(userAnswer, JSON.stringify(cloned));
                renderWordGroups(cloned);
                if (cloned.wordGroups && cloned.wordGroups.length > 0) {
                    renderWordsTable(cloned.wordGroups[0]);
                    renderWordsDetails(cloned.wordGroups[0]);
                }
            } else {
                // Clear old cache
                Object.keys(localStorage)
                    .filter((k) => k.startsWith(wordGroupsPrefix))
                    .forEach((k) => localStorage.removeItem(k));

                localStorage.setItem(storageKey, JSON.stringify(data));
                renderWordGroups(data);
                renderWordsTable(data.wordGroups[0]);
                renderWordsDetails(data.wordGroups[0]);
            }

            // Update URL in address bar
            history.pushState({}, "", `?verse_id=${verseId}`);
        },
        error: function () {
            alert("Terjadi kesalahan");
        },
    });
}

// =============================
// FETCH WORDS
// =============================
function fetchWords(word_group_id) {
    const tbodyWords = $("#sortable-table tbody");
    const tbodyWordsDetail = $("#detail-kalimat-table tbody");

    const key = Object.keys(localStorage).find((k) =>
        k.startsWith(wordGroupsPrefix),
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
    const activeGroup = stored.wordGroups.find((wg) => wg.id == word_group_id);

    if (!activeGroup || !activeGroup.words || activeGroup.words.length === 0) {
        const row = `
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
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
// RENDER WORDGROUPS SLIDER
// =============================
function renderWordGroups(data) {
    // Remove existing event listeners to prevent duplicates
    $slider.off("initialized.owl.carousel");
    $slider.off("translated.owl.carousel");

    $slider.trigger("destroy.owl.carousel");
    $slider.html("");

    data.wordGroups.forEach((wordGroup) => {
        $slider.append(`
      <div>
        <h4 class="arabic-text ar-title word-group" wg-id="${wordGroup.id}">${wordGroup.text}</h4>
      </div>`);
    });

    $slider.owlCarousel({ rtl: true, items: 1, dots: false, nav: false });

    // Re-add event listeners after initialization
    $slider.on("initialized.owl.carousel", (e) => {
        const id = getActiveWgId(e);
        if (id) fetchWords(id);
    });

    $slider.on("translated.owl.carousel", (e) => {
        const id = getActiveWgId(e);
        if (id) fetchWords(id);
    });

    currentSurahId.value = data.surah.id;
    currentVerseNumber.value = data.verse.number;
    currentVerseId.value = data.verse.id;

    surahOption.value = "";
    verseOption.value = "";

    currentVerseLabel.textContent = `${data.surah.id}. ${data.surah.name} - Ayat ${data.verse.number}`;

    // update wordgroup editor info
    const firstWordGroup = data.wordGroups[0];
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
            const groupingUrl = `/wordgroups/grouping?verse_id=${data.verse.id}`;
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
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
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

        const isAnswerMode = wordGroupsPrefix === "answer_user_";
        const actionButtons = isAnswerMode
            ? `<button class="btn btn-sm btn-icon btn-warning word-edit" title="Edit">Edit 
                   <i class="fa-solid fa-edit"></i>
               </button>`
            : `<button class="btn btn-sm btn-icon btn-warning word-edit" title="Edit">
                   <i class="fa-solid fa-edit"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-danger word-delete" title="Hapus">
                   <i class="fa-solid fa-trash"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-primary btn-move-up" title="Naikkan">
                   <i class="fa-solid fa-arrow-up"></i>
               </button>
               <button class="btn btn-sm btn-icon btn-primary btn-move-down" title="Turunkan">
                   <i class="fa-solid fa-arrow-down"></i>
               </button>`;

        const row = `
            <tr>
            <td class="align-middle col-action">
                    <div class="d-flex justify-content-center action-buttons">
                        ${actionButtons}
                    </div>
                </td>
                <td class="text-center align-middle col-word">
                    <div class="${simbolClass} arabic-text words" id="${word.id}">${word.text}</div>
                </td>
                <td class="text-center align-middle col-kalimat">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kalimat ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-hukum">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.hukum ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-kategori">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kategori ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-kedudukan">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.kedudukan ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-irob">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.irob ?? ""
                    }</div>
                </td>
                <td class="text-center align-middle col-tanda">
                    <div class="text-center mb-2 arabic-text ar-symbol">${
                        word.tanda ?? ""
                    }</div>
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

    applyComparisonHighlights();
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
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
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

// Exercise page submit handler
if (window.PAGE_TYPE === "exercise") {
    const btnSubmitExercise = document.getElementById("btn-submit-exercise");
    if (btnSubmitExercise) {
        btnSubmitExercise.addEventListener("click", (e) => {
            e.preventDefault();

            const verseId = currentVerseId.value;
            if (!verseId) {
                iziToast.warning({
                    message: "Verse ID tidak ditemukan",
                    position: "topRight",
                });
                return;
            }

            const compareResult = compareAnswers(verseId);
            if (compareResult.length === 0) {
                iziToast.warning({
                    message: "Tidak ada data untuk dibandingkan",
                    position: "topRight",
                });
                return;
            }

            currentCompareResult = compareResult;
            currentCompareVerseId = verseId;

            highlightErrors(compareResult);

            const totalAnswers = compareResult.length;
            const correctAnswers = compareResult.filter(
                (r) => r.correct,
            ).length;
            const wrongAnswers = totalAnswers - correctAnswers;
            const score = Math.round((correctAnswers / totalAnswers) * 100);

            if (score === 100) {
                swal({
                    icon: "success",
                    title: "Selamat",
                    text: "Anda dapat melanjutkan ke soal selanjutnya",
                    buttons: {
                        cancel: {
                            text: "Kembali",
                            visible: true,
                        },
                        confirm: {
                            text: "Selanjutnya",
                            visible: true,
                            className: "btn-success",
                        },
                    },
                }).then((next) => {
                    const nextVerse = Number(currentVerseId.value) + 1;
                    fetchWordGroups(null, null, nextVerse)
                });
            } else {
                iziToast.warning({
                    message: `${wrongAnswers} jawaban salah. Mohon cek kembali`,
                    position: "bottomRight",
                    timeout: 5000,
                });
            }
        });
    }
}

// =============================
// DOM
// =============================
document.addEventListener("DOMContentLoaded", () => {
    fetchSurahList();

    const cachedKey = getActiveStorageKey(wordGroupsPrefix);
    const cachedRaw = cachedKey ? localStorage.getItem(cachedKey) : null;

    // ------------------------------------------------------
    // FETCH NEW
    // ------------------------------------------------------
    if (!cachedRaw) {
        fetchWordGroups(null, null, 1);
        return;
    }

    // ------------------------------------------------------
    // RESTORE CACHE
    // ------------------------------------------------------

    const cachedData = JSON.parse(cachedRaw);

    renderWordGroups(cachedData);
    renderWordsTable(cachedData.wordGroups[0]);
    renderWordsDetails(cachedData.wordGroups[0]);

    if (cachedData.modified) {
        addRefreshButton();

        iziToast.info({
            message: "Data sebelumnya berhasil dipulihkan",
            position: "bottomCenter",
        });
    }

    // Note: fetchWords for firstGroup is handled by carousel initialized event in renderWordGroups
});

$(document).ajaxStart(showLoading);
$(document).ajaxStop(hideLoading);
