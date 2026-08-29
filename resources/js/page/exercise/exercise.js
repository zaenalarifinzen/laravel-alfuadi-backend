import * as storage from "../../utils/storage-helper";
import { createNahwuFormController } from "../../page/words/nahwu-form-autofill";
import { initAnalysisAnswerHandler } from "./answer-handler";
import { initAnalysisPage } from "./analysis-page";
import { initSearchVerse } from "../../utils/search-verse";
import { initSwiperSlider } from "../../components/swiper-slider";
import { initWordTable } from "../../components/word-table";
import { initComponentsTable } from "../../page/components-table";

function readPageConfig() {
    const configEl = document.getElementById("page-config");
    if (!configEl) {
        throw new Error("page-config tidak ditemukan");
    }

    const config = JSON.parse(configEl.textContent);

    return {
        ...config,
        pageType: config.pageType ?? config.pegeType,
    };
}

function collectElements() {
    return {
        currentExerciseOrderNumber: document.getElementById("exercise-id"),
        currentWordGroupLabel: document.getElementById("current-wordgroup-label"),
        currentVerseId: document.getElementById("verse-id"),
        surahOption: document.getElementById("surah-option"),
        verseOption: document.getElementById("verse-option"),
        searchForm: document.getElementById("search-verse-form"),
        btnPrevVerse: document.getElementById("btn-prev-verse"),
        btnNextVerse: document.getElementById("btn-next-verse"),
    };
}

const config = readPageConfig();
const elements = collectElements();

let analysisPage;
let wordTable;
let slider;
let nahwuFormController = null;

analysisPage = initAnalysisPage({
    config,
    elements,
    storage,
    getWordTable: () => wordTable,
    getSlider: () => slider,
});

wordTable = initWordTable({
    mode: 'exercise',
    getPrefix: analysisPage.getPrefix,
    isModified: storage.isModified,
    showEditConfirmation: analysisPage.showEditConfirmation,
    fetchExercise: analysisPage.fetchExercise,
    getCurrentVerseId: analysisPage.getCurrentVerseId,
    applyComparisonHighlights: analysisPage.applyComparisonHighlights,
    changeSubmitButton: analysisPage.changeSubmitButton,
});

slider = initSwiperSlider({
    fetchWords: analysisPage.fetchWords,
    elements,
});

initSearchVerse({
    elements,
    getPrefix: analysisPage.getPrefix,
    isModified: storage.isModified,
    showEditConfirmation: analysisPage.showEditConfirmation,
    fetchExercise: analysisPage.fetchExercise,
    onSearch: analysisPage.searchVersebyNumber,
    onNavigate: analysisPage.fetchExercise,
    config,
}).fetchSurahList();

initComponentsTable({
    getPrefix: analysisPage.getPrefix,
    markModified: storage.markModified,
    renderWordsDetails: wordTable.renderWordsDetails,
});

initAnalysisAnswerHandler({
    getPrefix: analysisPage.getPrefix,
    markModified: storage.markModified,
    renderWordsTable: wordTable.renderWordsTable,
    renderWordsDetails: wordTable.renderWordsDetails,
    getNahwuController: () => nahwuFormController,
    getCurrentCompareResult: analysisPage.getCurrentCompareResult,
    setCurrentCompareResult: analysisPage.setCurrentCompareResult,
    getCurrentExerciseState: analysisPage.getCurrentExerciseState,
    getCachedExerciseData: analysisPage.getCachedExerciseData,
    saveCachedExerciseData: analysisPage.saveCachedExerciseData,
    fetchExercise: analysisPage.fetchExercise,
    compareAnswers: analysisPage.compareAnswers,
    highlightErrors: analysisPage.highlightErrors,
    changeSubmitButton: analysisPage.changeSubmitButton,
    resetCard: wordTable.resetCard,
});

document.addEventListener("DOMContentLoaded", () => {
    nahwuFormController = createNahwuFormController();
    analysisPage.boot();
});