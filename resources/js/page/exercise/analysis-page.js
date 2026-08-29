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
    let exerciseCacheKey = null;
    let currentExercisOrderNumber = null;
    let currentCompareResult = [];
    let currentCompareExerciseId = null;

    function getPrefix() {
        return exerciseCacheKey;
    }

    function compareAnswers() {
        const exerciseRaw = localStorage.getItem(getPrefix());

        if (!exerciseRaw) {
            console.warn("Answer key atau user answer tidak ditemukan");
            return [];
        }

        const exerciseData = JSON.parse(exerciseRaw);

        const correctAnswer = exerciseData.wordGroups;
        const userAnswer = exerciseData.userAnswer;
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

        correctAnswer.forEach((keyGroup) => {
            const userGroup = userAnswer.find((g) => g.id === keyGroup.id);
            if (!userGroup) return;

            keyGroup.words.forEach((keyWord) => {
                const userWord = userGroup.words.find(
                    (w) => String(w.id) === String(keyWord.id),
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

    function getCurrentExerciseState() {
        const cachedData = getCachedExercise(exerciseCacheKey);

        return {
            orderNumber: cachedData?.exerciseOrderNumber ?? null,
            levelSlug: cachedData?.levelSlug ?? null,
        };
    }

    function isCacheStillValid(cachedData, freshContent) {
        const cachedWords = cachedData?.wordGroups?.[0]?.words;
        const freshWords = freshContent?.wordGroups?.[0]?.words;

        if (!cachedWords?.length || !freshWords?.length) return false;

        return cachedWords[0].updated_at === freshWords[0].updated_at;
    }

    function clearExerciseStorage() {
        Object.keys(localStorage)
            .filter((k) => k.startsWith("ex_"))
            .forEach((k) => localStorage.removeItem(k));
    }

    // ---------------------------------------------------------------------------
    // Answer payload building / persistence
    // ---------------------------------------------------------------------------

    function buildAnswerPayload(content, exerciseData, orderNumber) {
        const cloned = structuredClone(content);
        const wordGroups = structuredClone(cloned.wordGroups);

        cloned.modified = false;
        cloned.levelSlug = exerciseData.exercise_level.slug;
        cloned.exerciseOrderNumber = orderNumber;
        cloned.passed = exerciseData.passed;
        cloned.title = exerciseData.title;
        cloned.userAnswer = cloned.passed
            ? wordGroups
            : stripAnswerFields(wordGroups);
        return cloned;
    }

    function stripAnswerFields(wordGroups) {
        wordGroups.forEach((wg) => {
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

        return wordGroups;
    }

    function persistExerciseData(answerKey, userAnswerKey, clonedContent) {
        localStorage.setItem(answerKey, JSON.stringify(clonedContent));
        localStorage.setItem(userAnswerKey, JSON.stringify(clonedContent));
    }

    // ---------------------------------------------------------------------------
    // Rendering / UI state
    // ---------------------------------------------------------------------------

    function renderExercise(clonedContent) {
        const slider = getSlider();
        const wordTable = getWordTable();

        slider.renderSwiperSlider(clonedContent);
        wordTable.renderWordsTable(clonedContent.userAnswer[0]);
        wordTable.renderWordsDetails(clonedContent.wordGroups[0]);

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

        const exerciseLevel = exerciseData.exercise_level.slug;
        const exerciseNumber = resolveOrderNumber(exerciseData);
        exerciseCacheKey = `ex_${exerciseLevel}_${exerciseNumber}`;

        const cachedData = getCachedExercise(exerciseCacheKey);
        const passed = exerciseData.passed;

        if (isCacheStillValid(cachedData, content)) {
            loadCachedData();
            return;
        }

        currentCompareResult = [];
        currentCompareExerciseId = null;

        clearExerciseStorage();

        const exerciseKeyPayload = buildAnswerPayload(
            content,
            exerciseData,
            exerciseNumber,
        );
        localStorage.setItem(
            exerciseCacheKey,
            JSON.stringify(exerciseKeyPayload),
        );

        const wordTable = renderExercise(exerciseKeyPayload);
        updateSubmitState(wordTable, passed);
        syncUrlToHistory(exerciseLevel, exerciseNumber);
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
        const cachedData = getCachedExercise(exerciseCacheKey);

        const setEmpty = (text) => {
            const row = `<tr><td colspan="8" class="text-center text-muted">${text}</td></tr>`;
            tbodyWords.html(row);
            tbodyWordsDetail.html(row);
        };

        if (!cachedData || !Array.isArray(cachedData.wordGroups)) {
            setEmpty("Tidak ada data");
            return;
        }

        // Render User Answer
        const activeUserAnswerGroup = cachedData.userAnswer.find(
            (wg) => wg.id == wordGroupId,
        );
        const wordTable = getWordTable();

        if (
            !activeUserAnswerGroup ||
            !activeUserAnswerGroup.words ||
            activeUserAnswerGroup.words.length === 0
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
        wordTable.renderWordsTable(activeUserAnswerGroup);

        // Render Aswer Key
        const answerKeyGroup = cachedData.wordGroups.find(
            (wg) => wg.id == wordGroupId,
        );

        if (answerKeyGroup) {
            wordTable.renderWordsDetails(answerKeyGroup);
        }
    }

    function loadCachedData() {
        const cachedKey = storage.getActiveStorageKey(exerciseCacheKey);
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

        slider.renderSwiperSlider(cachedData);
        wordTable.renderWordsTable(cachedData.userAnswer[0]);
        wordTable.renderWordsDetails(cachedData.wordGroups[0]);

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
        setCurrentCompareResult: (compareResult) => {
            currentCompareResult = compareResult;
        },
        getCurrentCompareVerseId: () => currentCompareExerciseId,
        getCurrentExerciseState,
    };
}
