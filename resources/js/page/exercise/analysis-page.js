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
            beforeSend: function () {
                showLoading();
            },
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

                // Compare response and cache
                const cachedKey = storage.getActiveStorageKey(answerKey);
                const cachedRaw = cachedKey
                    ? localStorage.getItem(cachedKey)
                    : null;
                const wordTable = getWordTable();
                const slider = getSlider();
                const cachedData = JSON.parse(cachedRaw);

                // If cached data exists, compare timestamps to determine if the data has changed
                if (cachedData && cachedData.wordGroups?.length > 0) {
                    const oldTimestamp =
                        cachedData.wordGroups[0].words[0].updated_at;
                    const newTimestamp =
                        content.wordGroups[0].words[0].updated_at;

                    if (oldTimestamp === newTimestamp) {
                        loadCachedData();
                        return;
                    }
                }

                content.modified = false;
                content.questionId = dataQuestion.id;
                content.passed = dataQuestion.passed;

                // wordTable.removeUpdateButton();
                currentQuestionId = dataQuestion?.id;
                currentCompareResult = [];
                currentCompareVerseId = null;

                // Remove cached answer_key_/answer_user_ entries for OTHER verses only.
                // Preserves any user-edited data for the current verse so it won't be
                // unexpectedly overwritten when navigating between wordgroups.
                Object.keys(localStorage)
                    .filter((k) =>
                        k.startsWith("answer_key_") || k.startsWith("answer_user_"),
                    )
                    .filter((k) => k !== `answer_key_${content.verse.id}` && k !== `answer_user_${content.verse.id}`)
                    .forEach((k) => localStorage.removeItem(k));

                const cloned = structuredClone(content);
                const passed = dataQuestion.passed;

                localStorage.setItem(answerKey, JSON.stringify(cloned));

                // Write user answer only if there is no existing user answer for
                // this verse, or if the existing one is not marked as modified.
                // This avoids clobbering local user edits when reloading/fetching
                // the same verse from the server.
                const existingUserRaw = localStorage.getItem(userAnswer);
                if (existingUserRaw) {
                    try {
                        const existingUser = JSON.parse(existingUserRaw);
                        if (!existingUser.modified) {
                            localStorage.setItem(userAnswer, JSON.stringify(cloned));
                        }
                        // otherwise preserve user's modified data
                    } catch (e) {
                        // fallback: overwrite if parsing fails
                        localStorage.setItem(userAnswer, JSON.stringify(cloned));
                    }
                } else {
                    localStorage.setItem(userAnswer, JSON.stringify(cloned));
                }

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
                    changeSubmitButton(
                        "btn-submit-answer",
                        "Submit",
                        "primary",
                    );
                }

                slider.renderSwiperSlider(cloned);

                const firstWordGroup = cloned.wordGroups[0];
                wordTable.renderWordsTable(firstWordGroup);

                const answerKeyWordGroup = content.wordGroups[0];
                wordTable.renderWordsDetails(answerKeyWordGroup);

                if (passed) {
                    wordTable.resetCard();
                    wordTable.updateCard("Selesai", "success");
                    changeSubmitButton(
                        "btn-next-verse",
                        "Selanjutnya",
                        "primary",
                    );
                }
                history.pushState({}, "", `?verse_id=${verseId}`);
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert("Terjadi kesalahan");
            },
            complete: function () {
                hideLoading();
            },
        });
    }

    function fetchWords(wordGroupId) {
        const tbodyWords = $("#sortable-table tbody");
        const tbodyWordsDetail = $("#detail-kalimat-table tbody");
        const key = storage.getActiveStorageKey(wordGroupsPrefix);

        const setEmpty = (text) => {
            const row = `<tr><td colspan="8" class="text-center text-muted">${text}</td></tr>`;
            tbodyWords.html(row);
            tbodyWordsDetail.html(row);
        }

        if (!key) {
            setEmpty("Memuat data")
            return;
        }

        let stored;
        try {
            stored = JSON.parse(localStorage.getItem(key));
        } catch (e) {
            console.error("Gagal parse data kata:", e);
            setEmpty("Terjadi kesalahan memuat data");
            return;
        }

        if (!stored || !Array.isArray(stored.wordGroups)) {
            setEmpty("Tidak ada data");
            return;
        }
        
        const activeGroup = stored.wordGroups.find(
            (wg) => wg.id == wordGroupId,
        );
        const wordTable = getWordTable();

        if (!activeGroup || !activeGroup.words || activeGroup.words.length === 0
        ) {
            setEmpty("Tidak ada data");
            var lastNode = $(".editor-kalimat a").contents().last()[0];
            if (lastNode) {
                lastNode.textContent += " -";
            } else {
                $(".editor-kalimat a").last().text(" -");
            }
            return;
        }

        wordTable.renderWordsTable(activeGroup);

        if (!stored.verse || !stored.verse.id) return;
        
        // Load Aswer Key
        const answerKeyRaw = localStorage.getItem(`answer_key_${stored.verse.id}`);
        if (!answerKeyRaw) return;

        try {
            const answerKeyData = JSON.parse(answerKeyRaw);
            const answerGroup = answerKeyData.wordGroups.find(
                (wg) => wg.id == wordGroupId,
            );
            if (answerGroup) {
                wordTable.renderWordsDetails(answerGroup);
            }
        } catch (e) {
            console.error("Gagal parse answer key:", e);
        }
    }

    function loadCachedData() {
        const cachedKey = storage.getActiveStorageKey(wordGroupsPrefix);
        const cachedRaw = cachedKey ? localStorage.getItem(cachedKey) : null;

        if (!cachedRaw) return;

        let cachedData;
        try {
            cachedData = JSON.parse(cachedRaw);
        } catch (e) {
            console.error("Failed parse cache:", e);
        }

        if (!cachedData) return;

        const wordTable = getWordTable();
        const slider = getSlider();

        const currentQuestionId = cachedData.questionId;
        slider.renderSwiperSlider(cachedData);
        wordTable.renderWordsTable(cachedData.wordGroups[0]);

        // if exercise mode, get wordGroup from answer_key to WordDetails Table
        const answerKey = `answer_key_${cachedData.verse.id}`;
        const answerKeyRaw = localStorage.getItem(answerKey);
        if (answerKeyRaw) {
            const answerKeyData = JSON.parse(answerKeyRaw);
            wordTable.renderWordsDetails(answerKeyData.wordGroups[0]);
        }

        if (cachedData.modified) {
            // wordTable.addUpdateButton();
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

    function boot() {
        const cachedKey = storage.getActiveStorageKey(wordGroupsPrefix);
        const cachedRaw = cachedKey ? localStorage.getItem(cachedKey) : null;
        const cachedData = JSON.parse(cachedRaw);
        const cachedVerseId = cachedData?.verse?.id;

        fetchWordGroups(null, null, cachedVerseId || 1);
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
