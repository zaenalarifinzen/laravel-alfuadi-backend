/* ==========================================================================
   File: analysis-page.js
   Description: Handles Exercise Page
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
const currentSurahId = document.getElementById("surah-id");
const currentVerseNumber = document.getElementById("verse-number");
const currentVerseId = document.getElementById("verse-id");

let currentQuestionId = null;
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
// FETCH WORDGROUPS
// =============================
function fetchWordGroups(surah_id, verse_number, verse_id) {
    let url;

    if (verse_id) {
        url = ANALYSIS_GET_URL.replace(":id", verse_id);
    } else if (surah_id && verse_number) {
        url = ANALYSIS_GET_URL.replace(
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
            const data = response?.data?.content;         

            if (!data || !data.verse) {
                console.error("Invalid response data");
                return;
            }
            
            const hasWords =
                Array.isArray(data.wordGroups[0]?.words) &&
                data.wordGroups[0]?.words.length > 0;

            if (!hasWords) {
                swal({
                    title: "Ayat belum tersedia",
                    text: "Silakan coba ayat lainnya",
                    icon: "error",
                    buttons: {
                        confirm: {
                            text: "Tutup",
                            visible: true,
                        },
                    },
                });
                return;
            }

            const verseId = data.verse.id;
            const answerKey = `answer_key_${verseId}`;
            const userAnswer = `answer_user_${verseId}`;

            data.modified = false;
            data.questionId = response?.data?.id;

            removeRefreshButton();

            currentQuestionId = response?.data?.id;
            currentCompareResult = [];
            currentCompareVerseId = null;

            // cleanup localstorage
            Object.keys(localStorage)
                .filter(
                    (k) =>
                        k.startsWith("answer_key_") ||
                        k.startsWith("answer_user_"),
                )
                .forEach((k) => localStorage.removeItem(k));

            const cloned = structuredClone(data);

            localStorage.setItem(answerKey, JSON.stringify(cloned));

            cloned.wordGroups.forEach((wg) => {
                if (!Array.isArray(wg.words)) return;

                wg.words.forEach((w) => {
                    Object.assign(w, {
                        color : null,
                        kalimat : null,
                        hukum : null,
                        kategori : null,
                        kedudukan : null,
                        irob : null,
                        tanda : null,
                    })
                });
            });

            localStorage.setItem(userAnswer, JSON.stringify(cloned));

            renderOwlSlider(cloned);

            const firstWordGroup = cloned.wordGroups[0];

            renderWordsTable(firstWordGroup);
            renderWordsDetails(firstWordGroup);

            // Update URL in address bar
            history.pushState({}, "", `?verse_id=${verseId}`);
        },
        error: function (xhr, status, error) {
            console.error(error);
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

    currentQuestionId = cachedData.questionId;
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
