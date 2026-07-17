import * as storage from "../../page/words/storage-helper";
import { createNahwuFormController } from "../../page/words/nahwu-form-autofill";
import { initAnalysisAnswerHandler } from "./analysis-answer-handler";
import { initAnalysisPage } from "./analysis-page";
import { initSearchVerse } from "../../utils/search-verse";
import { initSwiperSlider } from "../../utils/swiper-slider";
import { initWordTable } from "../../utils/word-table";
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
        currentVerseLabel: document.getElementById("current-verse-label"),
        currentSurahId: document.getElementById("surah-id"),
        currentVerseNumber: document.getElementById("verse-number"),
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
    getPrefix: analysisPage.getPrefix,
    isModified: storage.isModified,
    showEditConfirmation: analysisPage.showEditConfirmation,
    fetchWordGroups: analysisPage.fetchWordGroups,
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
    fetchWordGroups: analysisPage.fetchWordGroups,
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
    getCurrentQuestionId: analysisPage.getCurrentQuestionId,
    getCurrentVerseId: analysisPage.getCurrentVerseId,
    fetchWordGroups: analysisPage.fetchWordGroups,
    compareAnswers: analysisPage.compareAnswers,
    highlightErrors: analysisPage.highlightErrors,
    changeSubmitButton: analysisPage.changeSubmitButton,
    resetCard: wordTable.resetCard,
});

document.addEventListener("DOMContentLoaded", () => {
    nahwuFormController = createNahwuFormController();
    analysisPage.boot();
});
