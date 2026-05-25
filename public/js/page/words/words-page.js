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
// GLOBAL ELEMENTS
// =============================
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
                renderOwlSlider(cloned);
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
                renderOwlSlider(data);
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

function changeSubmitButton(id, label, type) {
    // get from name attribute
    const submitBtn = document.querySelector(`button[name="btn-submit"]`);
    if (submitBtn) {
        // change id from parameter: id
        submitBtn.id = id;
        submitBtn.textContent = label;

        submitBtn.classList.remove("btn-success", "btn-secondary", "btn-primary", "btn-danger");
        submitBtn.classList.add(`btn-${type}`);
    }
}

// =============================
// DOM
// =============================
document.addEventListener("DOMContentLoaded", () => {
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

    renderOwlSlider(cachedData);
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
