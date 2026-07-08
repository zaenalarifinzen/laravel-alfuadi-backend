/* ==========================================================================
   Handles Exercise Analysis Page
   ========================================================================== */

export function initAnalysisPage({
    config,
    elements,
    storage,
    getWordTable,
    getSlider,
}) {
    const wordGroupsPrefix =
        config.pageType === "exercise" ? "answer_user_" : "wordgroups_";

    let currentQuestionId = null;
    let currentCompareResult = [];
    let currentCompareVerseId = null;

    function getPrefix() {
        return wordGroupsPrefix;
    }

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
                const userWord = userGroup.words.find(
                    (w) => w.id === keyWord.id,
                );
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
                return;
            }

            tr.classList.remove("is-correct");
            tr.classList.add("is-wrong");

            item.fields.forEach((fieldResult) => {
                if (fieldResult.correct) return;

                const colClassByField = {
                    kalimat: ".col-kalimat",
                    hukum: ".col-hukum",
                    kategori: ".col-kategori",
                    kedudukan: ".col-kedudukan",
                    irob: ".col-irob",
                    tanda: ".col-tanda",
                };
                const colClass = colClassByField[fieldResult.field];
                if (!colClass) return;

                const td = tr.querySelector(colClass);
                if (td) td.classList.add("is-wrong");
            });
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

    function changeSubmitButton(id, label, type) {
        const submitBtn = document.querySelector(`button[name="btn-submit"]`);
        if (!submitBtn) return;

        submitBtn.id = id;
        submitBtn.textContent = label;
        submitBtn.classList.remove(
            "btn-success",
            "btn-secondary",
            "btn-primary",
            "btn-danger",
        );
        submitBtn.classList.add(`btn-${type}`);
    }

    function fetchWordGroups(surahId, verseNumber, verseId) {
        let url;

        if (verseId) {
            url = config.wordgroupGetUrl.replace(":id", verseId);
        } else if (surahId && verseNumber) {
            url = config.wordgroupGetUrl.replace(
                "/:id",
                `?surah_id=${surahId}&verse_number=${verseNumber}`,
            );
        } else {
            alert("Parameter tidak lengkap");
            return;
        }

        $.ajax({
            url,
            type: "GET",
            success: function (response) {
                const dataQuestion = response?.data;
                const content = dataQuestion?.content;

                if (!content || !content.verse) {
                    console.error("Invalid response data");
                    return;
                }

                const hasWords =
                    Array.isArray(content.wordGroups[0]?.words) &&
                    content.wordGroups[0]?.words.length > 0;

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

                const verseId = content.verse.id;
                const answerKey = `answer_key_${verseId}`;
                const userAnswer = `answer_user_${verseId}`;
                const wordTable = getWordTable();
                const slider = getSlider();

                content.modified = false;
                content.questionId = dataQuestion.id;
                content.passed = dataQuestion.passed;

                wordTable.removeUpdateButton();
                currentQuestionId = dataQuestion?.id;
                currentCompareResult = [];
                currentCompareVerseId = null;

                Object.keys(localStorage)
                    .filter(
                        (k) =>
                            k.startsWith("answer_key_") ||
                            k.startsWith("answer_user_"),
                    )
                    .forEach((k) => localStorage.removeItem(k));

                const cloned = structuredClone(content);
                const passed = dataQuestion.passed;

                localStorage.setItem(answerKey, JSON.stringify(cloned));

                if (!passed) {
                    cloned.wordGroups.forEach((wg) => {
                        if (!Array.isArray(wg.words)) return;

                        wg.words.forEach((w) => {
                            Object.assign(w, {
                                color: null,
                                kalimat: null,
                                hukum: null,
                                kategori: null,
                                kedudukan: null,
                                irob: null,
                                tanda: null,
                            });
                        });
                    });

                    wordTable.resetCard();
                    changeSubmitButton("btn-submit-answer", "Submit", "primary");
                }

                localStorage.setItem(userAnswer, JSON.stringify(cloned));

                slider.renderOwlSlider(cloned);

                const firstWordGroup = cloned.wordGroups[0];
                wordTable.renderWordsTable(firstWordGroup);
                wordTable.renderWordsDetails(firstWordGroup);

                if (passed) {
                    wordTable.resetCard();
                    wordTable.updateCard("Selesai", "success");
                    changeSubmitButton("btn-next-verse", "Selanjutnya", "primary");
                }

                history.pushState({}, "", `?verse_id=${verseId}`);
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert("Terjadi kesalahan");
            },
        });
    }

    function fetchWords(wordGroupId) {
        const tbodyWords = $("#sortable-table tbody");
        const tbodyWordsDetail = $("#detail-kalimat-table tbody");
        const key = storage.getActiveStorageKey(wordGroupsPrefix);

        if (!key) {
            const row = `
            <tr>
                <td colspan="8" class="text-center text-muted">Memuat...</td>
            </tr>
        `;
            tbodyWords.html(row);
            tbodyWordsDetail.html(row);
            return;
        }

        const stored = JSON.parse(localStorage.getItem(key));
        const activeGroup = stored.wordGroups.find((wg) => wg.id == wordGroupId);
        const wordTable = getWordTable();

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

        wordTable.renderWordsTable(activeGroup);
        wordTable.renderWordsDetails(activeGroup);
    }

    function boot() {
        $(document).ajaxStart(showLoading);
        $(document).ajaxStop(hideLoading);

        const cachedKey = storage.getActiveStorageKey(wordGroupsPrefix);
        const cachedRaw = cachedKey ? localStorage.getItem(cachedKey) : null;
        const wordTable = getWordTable();
        const slider = getSlider();

        if (!cachedRaw) {
            fetchWordGroups(null, null, elements.currentVerseId.value || 1);
            return;
        }

        const cachedData = JSON.parse(cachedRaw);

        currentQuestionId = cachedData.questionId;
        slider.renderOwlSlider(cachedData);
        wordTable.renderWordsTable(cachedData.wordGroups[0]);
        wordTable.renderWordsDetails(cachedData.wordGroups[0]);

        if (cachedData.modified) {
            wordTable.addUpdateButton();
            changeSubmitButton("btn-submit-answer", "Submit", "primary");

            iziToast.info({
                message: "Data sebelumnya berhasil dipulihkan",
                position: "bottomCenter",
            });
        }

        if (cachedData.passed) {
            changeSubmitButton("btn-next-verse", "Selanjutnya", "primary");
            wordTable.updateCard("Selesai", "success");
        }
    }

    return {
        boot,
        getPrefix,
        fetchWordGroups,
        fetchWords,
        compareAnswers,
        highlightErrors,
        applyComparisonHighlights,
        showEditConfirmation,
        changeSubmitButton,
        getCurrentQuestionId: () => currentQuestionId,
        getCurrentCompareResult: () => currentCompareResult,
        setCurrentCompareResult: (compareResult, verseId = null) => {
            currentCompareResult = compareResult;
            currentCompareVerseId = verseId;
        },
        getCurrentCompareVerseId: () => currentCompareVerseId,
        getCurrentVerseId: () => elements.currentVerseId.value,
    };
}
