/* ==========================================================================
   Handles I'rob Input Page (Words)
   ========================================================================== */

export function initWordsPage({
    config,
    elements,
    storage,
    getWordTable,
    getSlider,
}) {
    const wordGroupsPrefix =
        config.pageType === "exercise" ? "answer_user_" : "wordgroups_";

    let currentCompareResult = [];
    let currentCompareVerseId = null;

    function getPrefix() {
        return wordGroupsPrefix;
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
                const data = response.data;
                const verseId = data.verse.id;
                const storageKey = `${wordGroupsPrefix}${verseId}`;
                const wordTable = getWordTable();
                const slider = getSlider();

                // Compare response and cache
                const cachedKey = storage.getActiveStorageKey(storageKey);
                const cachedRaw = cachedKey
                    ? localStorage.getItem(cachedKey)
                    : null;
                const cachedData = JSON.parse(cachedRaw);
                // find latest updated_at timestamp from data.wordGroups
                const dataTimestampWordgroups = data.wordGroups.reduce(
                    (latest, wg) => {
                        const wgTimestamp = wg.updated_at;
                        return wgTimestamp > latest ? wgTimestamp : latest;
                    },
                    "1970-01-01T00:00:00Z",
                );

                // If cached data exists, compare timestamps to determine if the data has changed
                if (cachedData && cachedData.wordGroups?.length > 0) {
                    const cachedTimestampWordgroups =
                        cachedData.wordGroups.reduce((latest, wg) => {
                            const wgTimestamp = wg.updated_at;
                            return wgTimestamp > latest ? wgTimestamp : latest;
                        }, "1970-01-01T00:00:00Z");

                    if (cachedTimestampWordgroups === dataTimestampWordgroups) {
                        loadCachedData();
                        return;
                    }
                }

                data.modified = false;
                // wordTable.removeUpdateButton();

                if (config.pageType === "exercise") {
                    currentCompareResult = [];
                    currentCompareVerseId = null;
                    Object.keys(localStorage)
                        .filter(
                            (k) =>
                                k.startsWith("answer_key_") ||
                                k.startsWith("answer_user_"),
                        )
                        .forEach((k) => localStorage.removeItem(k));

                    const cloned = structuredClone(data);
                    const answerKey = `answer_key_${verseId}`;
                    const userAnswer = `answer_user_${verseId}`;

                    localStorage.setItem(answerKey, JSON.stringify(cloned));

                    if (
                        cloned.wordGroups &&
                        Array.isArray(cloned.wordGroups) &&
                        cloned.wordGroups.length > 0
                    ) {
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
                    slider.renderSwiperSlider(cloned);
                    if (cloned.wordGroups && cloned.wordGroups.length > 0) {
                        wordTable.renderWordsTable(cloned.wordGroups[0]);
                        wordTable.renderWordsDetails(cloned.wordGroups[0]);
                    }
                } else {
                    Object.keys(localStorage)
                        .filter((k) => k.startsWith(wordGroupsPrefix))
                        .forEach((k) => localStorage.removeItem(k));

                    localStorage.setItem(storageKey, JSON.stringify(data));
                    slider.renderSwiperSlider(data);
                    wordTable.renderWordsTable(data.wordGroups[0]);
                    wordTable.renderWordsDetails(data.wordGroups[0]);
                }

                history.pushState({}, "", `?verse_id=${verseId}`);
            },
            error: function () {
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
        const activeGroup = stored.wordGroups.find(
            (wg) => wg.id == wordGroupId,
        );
        const wordTable = getWordTable();

        if (
            !activeGroup ||
            !activeGroup.words ||
            activeGroup.words.length === 0
        ) {
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
            } else {
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

                    if (colClass) {
                        const td = tr.querySelector(colClass);
                        if (td) td.classList.add("is-wrong");
                    }
                });
            }
        });
    }

    function getCurrentCompareResult() {
        return currentCompareResult;
    }

    function setCurrentCompareResult(compareResult, verseId = null) {
        currentCompareResult = compareResult;
        currentCompareVerseId = verseId;
    }

    function loadCachedData() {
        const cachedKey = storage.getActiveStorageKey(wordGroupsPrefix);
        const cachedRaw = cachedKey ? localStorage.getItem(cachedKey) : null;
        const wordTable = getWordTable();
        const slider = getSlider();

        const cachedData = JSON.parse(cachedRaw);

        slider.renderSwiperSlider(cachedData);
        wordTable.renderWordsTable(cachedData.wordGroups[0]);
        wordTable.renderWordsDetails(cachedData.wordGroups[0]);

        if (cachedData.modified) {
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
        clearComparisonHighlights,
        applyComparisonHighlights,
        highlightErrors,
        showEditConfirmation,
        changeSubmitButton,
        getCurrentCompareResult,
        setCurrentCompareResult,
        getCurrentVerseId: () => elements.currentVerseId.value,
        getCurrentCompareVerseId: () => currentCompareVerseId,
    };
}
