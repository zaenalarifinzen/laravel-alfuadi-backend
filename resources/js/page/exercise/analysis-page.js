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
        config.pageType === "exercise" ? "au_" : "wordgroups_";

    let currentExercisOrderNumber = null;
    let currentCompareResult = [];
    let currentCompareVerseId = null;

    function getPrefix() {
        return wordGroupsPrefix;
    }

    function compareAnswers(exerciseOrderNumber) {
        const answerKeyRaw = localStorage.getItem(`ak_${exerciseOrderNumber}`);
        const answerUserRaw = localStorage.getItem(`au_${exerciseOrderNumber}`);

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

    // ---------------------------------------------------------------------------
    // Search Verse Helper
    // ---------------------------------------------------------------------------
    function searchVersebyNumber(surahNumber, verseNumber) {
        fetchExercise("alquran", null, surahNumber, verseNumber);
    }

    // ---------------------------------------------------------------------------
    // URL building
    // ---------------------------------------------------------------------------

    function buildExerciseUrl(
        levelSlug,
        exerciseOrderNumber,
        surahId,
        verseNumber,
    ) {
        if (levelSlug === "alquran") {
            if (exerciseOrderNumber) {
                return config.exerciseGetUrl
                    .replace(":level", "alquran")
                    .replace(":id", exerciseOrderNumber);
            }

            if (surahId && verseNumber) {
                const base = config.exerciseGetUrl
                    .replace(":level", "alquran")
                    .replace("/:id", "");
                return `${base}?surah_id=${surahId}&verse_number=${verseNumber}`;
            }

            return null; // caller decides how to handle "missing parameter"
        }

        if (exerciseOrderNumber) {
            return config.exerciseGetUrl
                .replace(":level", levelSlug)
                .replace(":id", exerciseOrderNumber);
        }

        return null;
    }

    // ---------------------------------------------------------------------------
    // Response validation / parsing helpers
    // ---------------------------------------------------------------------------

    function isValidExerciseContent(content) {
        return (
            !!content &&
            Array.isArray(content.wordGroups?.[0]?.words) &&
            content.wordGroups[0].words.length > 0
        );
    }

    function resolveOrderNumber(exerciseData) {
        return exerciseData.display_order
            ? exerciseData.display_order
            : exerciseData.verse_id;
    }

    // ---------------------------------------------------------------------------
    // Cache handling
    // ---------------------------------------------------------------------------

    function getCachedExercise(answerKey) {
        const cachedKey = storage.getActiveStorageKey(answerKey);
        if (!cachedKey) return null;

        const cachedRaw = localStorage.getItem(cachedKey);
        if (!cachedRaw) return null;

        try {
            return JSON.parse(cachedRaw);
        } catch (e) {
            console.error("Failed to parse cached exercise data", e);
            return null;
        }
    }

    function isCacheStillValid(cachedData, freshContent) {
        const cachedWords = cachedData?.wordGroups?.[0]?.words;
        const freshWords = freshContent?.wordGroups?.[0]?.words;

        if (!cachedWords?.length || !freshWords?.length) return false;

        return cachedWords[0].updated_at === freshWords[0].updated_at;
    }

    function clearExerciseStorage() {
        Object.keys(localStorage)
            .filter((k) => k.startsWith("ak_") || k.startsWith("au_"))
            .forEach((k) => localStorage.removeItem(k));
    }

    // ---------------------------------------------------------------------------
    // Answer payload building / persistence
    // ---------------------------------------------------------------------------

    function buildAnswerPayload(content, exerciseData, orderNumber) {
        const cloned = structuredClone(content);
        cloned.modified = false;
        cloned.levelSlug = exerciseData.exercise_level.slug;
        cloned.exerciseOrderNumber = orderNumber;
        cloned.passed = exerciseData.passed;
        cloned.title = exerciseData.title;
        return cloned;
    }

    function stripAnswerFields(clonedContent) {
        clonedContent.wordGroups.forEach((wg) => {
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
    }

    function persistExerciseData(answerKey, userAnswerKey, clonedContent) {
        localStorage.setItem(answerKey, JSON.stringify(clonedContent));
        localStorage.setItem(userAnswerKey, JSON.stringify(clonedContent));
    }

    // ---------------------------------------------------------------------------
    // Rendering / UI state
    // ---------------------------------------------------------------------------

    function renderExercise(clonedContent, answerKeyContent) {
        const slider = getSlider();
        const wordTable = getWordTable();

        slider.renderSwiperSlider(clonedContent);
        wordTable.renderWordsTable(clonedContent.wordGroups[0]);
        wordTable.renderWordsDetails(answerKeyContent.wordGroups[0]);

        return wordTable;
    }

    function updateSubmitState(wordTable, passed) {
        wordTable.resetCard();

        if (passed) {
            wordTable.updateCard("Selesai", "success");
            changeSubmitButton("btn-next-verse", "Selanjutnya", "primary");
        } else {
            changeSubmitButton("btn-submit-answer", "Submit", "primary");
        }
    }

    function syncUrlToHistory(levelSlug, orderNumber) {
        history.replaceState(null, "", `/exercise/${levelSlug}/${orderNumber}`);
    }

    // ---------------------------------------------------------------------------
    // Error / empty-state UI
    // ---------------------------------------------------------------------------

    function showExerciseUnavailableDialog() {
        swal({
            title: "Soal tidak tersedia",
            text: "Silakan coba soal lainnya.",
            icon: "error",
            buttons: {
                confirm: {
                    text: "Tutup",
                    visible: true,
                },
            },
        });
    }

    function handleExerciseError(error) {
        console.error(error);
        showExerciseUnavailableDialog();
    }

    // ---------------------------------------------------------------------------
    // Response orchestration (still one function, but now just coordinates
    // the pieces above instead of doing everything inline)
    // ---------------------------------------------------------------------------

    function handleExerciseResponse(response) {
        const exerciseData = response?.data;
        const content = exerciseData?.content;

        if (!content) {
            console.error("Invalid response data");
            return;
        }

        if (!isValidExerciseContent(content)) {
            showExerciseUnavailableDialog();
            return;
        }

        const orderNumber = resolveOrderNumber(exerciseData);
        const answerKey = `ak_${exerciseData.exercise_level.slug}_${orderNumber}`;
        const userAnswerKey = `au_${exerciseData.exercise_level.slug}_${orderNumber}`;

        const cachedData = getCachedExercise(answerKey);
        if (isCacheStillValid(cachedData, content)) {
            loadCachedData();
            return;
        }

        // Reset any in-memory comparison state left over from a previous exercise
        currentCompareResult = [];
        currentCompareVerseId = null;

        clearExerciseStorage();

        const answerKeyPayload = buildAnswerPayload(
            content,
            exerciseData,
            orderNumber,
        );
        localStorage.setItem(answerKey, JSON.stringify(answerKeyPayload));

        const userAnswerPayload = structuredClone(answerKeyPayload);
        const passed = exerciseData.passed;

        if (!passed) {
            stripAnswerFields(userAnswerPayload);
        }

        localStorage.setItem(userAnswerKey, JSON.stringify(userAnswerPayload));

        const wordTable = renderExercise(userAnswerPayload, answerKeyPayload);
        updateSubmitState(wordTable, passed);
        syncUrlToHistory(exerciseData.exercise_level.slug, orderNumber);
    }

    // ---------------------------------------------------------------------------
    // Public entry point
    // ---------------------------------------------------------------------------

    function fetchExercise(
        levelSlug,
        exerciseOrderNumber = null,
        surahId = null,
        verseNumber = null,
    ) {
        const url = buildExerciseUrl(
            levelSlug,
            exerciseOrderNumber,
            surahId,
            verseNumber,
        );

        if (!url) {
            alert("Missing parameter");
            return;
        }

        $.ajax({
            url,
            type: "GET",
            beforeSend: showLoading,
            success: handleExerciseResponse,
            error: (xhr, status, error) => handleExerciseError(error),
            complete: hideLoading,
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
        };

        if (!key) {
            setEmpty("Memuat data");
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

        if (
            !activeGroup ||
            !activeGroup.words ||
            activeGroup.words.length === 0
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
        const answerKeyRaw = localStorage.getItem(`ak_${stored.verse.id}`);
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

        const currentExerciseOrderNumber = cachedData.exerciseOrderNumber;
        slider.renderSwiperSlider(cachedData);
        wordTable.renderWordsTable(cachedData.wordGroups[0]);

        // if exercise mode, get wordGroup from ak_ to WordDetails Table
        const answerKey = `ak_${cachedData.verse?.id}`;
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

    // ---------------------------------------------------------------------------
    // Navigation
    // ---------------------------------------------------------------------------
    function nextExercise(levelSlug, currentExerciseOrderNumber) {
        const nextExerciseOrderNumber = currentExercisOrderNumber + 1;
        fetchExercise(levelSlug, nextExerciseOrderNumber);
    }

    function prevExercise(levelSlug, currentExerciseOrderNumber) {
        const prevExerciseOrderNumber = currentExercisOrderNumber - 1;
        fetchExercise(levelSlug, prevExerciseOrderNumber);
    }


    // ---------------------------------------------------------------------------
    // Search Verse Helper
    // ---------------------------------------------------------------------------
    function boot() {
        const urlParts = window.location.pathname.split("/");
        const levelSlug = urlParts[2] || "beginner";
        const exerciseOrderNumber = urlParts[3] || 1;
        fetchExercise(levelSlug, exerciseOrderNumber);
    }

    return {
        boot,
        getPrefix,
        fetchExercise,
        searchVersebyNumber,
        fetchWords,
        compareAnswers,
        highlightErrors,
        applyComparisonHighlights,
        showEditConfirmation,
        changeSubmitButton,
        getCurrentExerciseOrderNumber: () => currentExerciseOrderNumber,
        getCurrentCompareResult: () => currentCompareResult,
        setCurrentCompareResult: (compareResult, verseId = null) => {
            currentCompareResult = compareResult;
            currentCompareVerseId = verseId;
        },
        getCurrentCompareVerseId: () => currentCompareVerseId,
        getCurrentVerseId: () => elements.currentVerseId.value,
    };
}
