/* ==========================================================================
   Handles Exercise Analysis Page
   Controller ini hanya mengurus orkestrasi & state milik halaman
   (exerciseCacheKey, currentCompareResult, currentExerciseOrderNumber).
   Logic murni sudah dipindah ke:
     - exercise-service.js  (URL, validasi, cache, payload)
     - answer-comparator.js (compare & highlight)
     - ui-helpers.js        (loading, tombol submit, dialog)
   ========================================================================== */

import * as exerciseService from "./exercise-service";
import * as comparator from "./answer-comparator";
import * as ui from "./ui-helpers";

export function initAnalysisPage({
    config,
    elements,
    storage,
    getWordTable,
    getSlider,
}) {
    let exerciseCacheKey = null;
    let currentExerciseOrderNumber = null;
    let currentExerciseLevelSlug = null;
    let currentCompareResult = [];
    let currentCompareExerciseId = null;

    function getPrefix() {
        return exerciseCacheKey;
    }

    function compareAnswers() {
        return comparator.compareAnswers(getPrefix());
    }

    function applyComparisonHighlights() {
        if (!currentCompareResult || currentCompareResult.length === 0) return;
        comparator.highlightErrors(currentCompareResult);
    }

    // ---------------------------------------------------------------------------
    // Search Verse Helper
    // ---------------------------------------------------------------------------
    function searchVersebyNumber(surahNumber, verseNumber) {
        fetchExercise("alquran", null, surahNumber, verseNumber);
    }

    // ---------------------------------------------------------------------------
    // Response orchestration
    // ---------------------------------------------------------------------------

    function handleExerciseResponse(response) {
        const exerciseData = response?.data;
        const content = exerciseData?.content;

        if (!content) {
            console.error("Invalid response data");
            return;
        }

        if (!exerciseService.isValidExerciseContent(content)) {
            ui.showExerciseUnavailableDialog();
            return;
        }

        const exerciseLevel = exerciseData.exercise_level.slug;
        const exerciseNumber = exerciseService.resolveOrderNumber(exerciseData);
        exerciseCacheKey = `ex_${exerciseLevel}_${exerciseNumber}`;

        const cachedData = exerciseService.getCachedExercise(
            storage,
            exerciseCacheKey,
        );
        const passed = exerciseData.passed;

        if (exerciseService.isCacheStillValid(cachedData, content)) {
            loadCachedData();
            return;
        }

        currentCompareResult = [];
        currentCompareExerciseId = null;
        currentExerciseOrderNumber = exerciseNumber;
        currentExerciseLevelSlug = exerciseLevel;

        exerciseService.clearExerciseStorage();

        const exerciseKeyPayload = exerciseService.buildAnswerPayload(
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

    function handleExerciseError(error) {
        console.error(error);
        ui.showExerciseUnavailableDialog();
    }

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
            ui.changeSubmitButton("btn-next-verse", "Selanjutnya", "primary");
        } else {
            ui.changeSubmitButton("btn-submit-answer", "Submit", "primary");
        }
    }

    function syncUrlToHistory(levelSlug, orderNumber) {
        history.replaceState(null, "", `/exercise/${levelSlug}/${orderNumber}`);
    }

    // ---------------------------------------------------------------------------
    // Public entry point: fetch & load
    // ---------------------------------------------------------------------------

    function fetchExercise(
        levelSlug,
        exerciseOrderNumber = null,
        surahId = null,
        verseNumber = null,
    ) {
        const url = exerciseService.buildExerciseUrl(
            config,
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
            beforeSend: ui.showLoading,
            success: handleExerciseResponse,
            error: (xhr, status, error) => handleExerciseError(error),
            complete: ui.hideLoading,
        });
    }

    function fetchWords(wordGroupId) {
        const tbodyWords = $("#sortable-table tbody");
        const tbodyWordsDetail = $("#detail-kalimat-table tbody");
        const cachedData = exerciseService.getCachedExercise(
            storage,
            exerciseCacheKey,
        );

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

        // Render Answer Key
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

        currentExerciseOrderNumber = cachedData.exerciseOrderNumber ?? currentExerciseOrderNumber;
        currentExerciseLevelSlug = cachedData.levelSlug ?? currentExerciseLevelSlug;

        const wordTable = getWordTable();
        const slider = getSlider();

        slider.renderSwiperSlider(cachedData);
        wordTable.renderWordsTable(cachedData.userAnswer[0]);
        wordTable.renderWordsDetails(cachedData.wordGroups[0]);

        if (cachedData.modified) {
            // wordTable.addUpdateButton();
            ui.changeSubmitButton("btn-submit-answer", "Submit", "primary");

            iziToast.info({
                message: "Data sebelumnya berhasil dipulihkan",
                position: "bottomCenter",
            });
        }

        if (cachedData.passed) {
            ui.changeSubmitButton("btn-next-verse", "Selanjutnya", "primary");
            wordTable.updateCard("Selesai", "success");
        }
    }

    function getCurrentExerciseState() {
        const cachedData = getCachedExerciseData();

        return {
            orderNumber: cachedData?.exerciseOrderNumber ?? null,
            levelSlug: cachedData?.levelSlug ?? null,
        };
    }

    // Satu pintu masuk untuk baca/tulis cache soal saat ini, supaya modul
    // lain (mis. answer-handler) tidak perlu tahu detail format key cache.
    function getCachedExerciseData() {
        return exerciseService.getCachedExercise(storage, exerciseCacheKey);
    }

    function saveCachedExerciseData(data) {
        const cachedKey = storage.getActiveStorageKey(exerciseCacheKey);
        if (!cachedKey) return;
        localStorage.setItem(cachedKey, JSON.stringify(data));
    }

    // ---------------------------------------------------------------------------
    // Navigation
    // ---------------------------------------------------------------------------
    function nextExercise() {
        if (!currentExerciseLevelSlug || currentExerciseOrderNumber == null) return;
        fetchExercise(currentExerciseLevelSlug, currentExerciseOrderNumber + 1);
    }

    function prevExercise() {
        if (!currentExerciseLevelSlug || currentExerciseOrderNumber == null) return;
        fetchExercise(currentExerciseLevelSlug, currentExerciseOrderNumber - 1);
    }

    // ---------------------------------------------------------------------------
    // Boot
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
        fetchWords,
        searchVersebyNumber,
        compareAnswers,
        highlightErrors: comparator.highlightErrors,
        applyComparisonHighlights,
        showEditConfirmation: ui.showEditConfirmation,
        changeSubmitButton: ui.changeSubmitButton,
        nextExercise,
        prevExercise,
        getCurrentExerciseOrderNumber: () => currentExerciseOrderNumber,
        getCurrentCompareResult: () => currentCompareResult,
        setCurrentCompareResult: (compareResult) => {
            currentCompareResult = compareResult;
        },
        getCurrentCompareVerseId: () => currentCompareExerciseId,
        getCurrentExerciseState,
        getCachedExerciseData,
        saveCachedExerciseData,
    };
}