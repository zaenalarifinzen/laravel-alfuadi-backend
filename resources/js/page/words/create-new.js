// resources/js/page/words/create-new.js
import * as storage from "./storage-helper";
import { initSearchVerse } from "../../utils/search-verse";
import { initSwiperSlider } from "../../utils/swiper-slider";
import { initWordTable } from "../../utils/word-table";
import { initWordsPage } from "./words-page";
import { initComponentsTable } from "../../page/components-table";
import { initWordCrud } from "./word-crud";
import { createNahwuFormController } from "./nahwu-form-autofill";

function readPageConfig() {
    const configEl = document.getElementById("page-config");
    if (!configEl) {
        throw new Error("words-page-config tidak ditemukan");
    }

    return JSON.parse(configEl.textContent);
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

let wordsPage;
let wordTable;
let slider;
let nahwuFormController = null;

wordsPage = initWordsPage({
    config,
    elements,
    storage,
    getWordTable: () => wordTable,
    getSlider: () => slider,
});

wordTable = initWordTable({
    getPrefix: wordsPage.getPrefix,
    isModified: storage.isModified,
    showEditConfirmation: wordsPage.showEditConfirmation,
    fetchWordGroups: wordsPage.fetchWordGroups,
    getCurrentVerseId: wordsPage.getCurrentVerseId,
    applyComparisonHighlights: wordsPage.applyComparisonHighlights,
    changeSubmitButton: wordsPage.changeSubmitButton,
});

slider = initSwiperSlider({
    fetchWords: wordsPage.fetchWords,
    elements,
});

initSearchVerse({
    elements,
    getPrefix: wordsPage.getPrefix,
    isModified: storage.isModified,
    showEditConfirmation: wordsPage.showEditConfirmation,
    fetchWordGroups: wordsPage.fetchWordGroups,
}).fetchSurahList();

initComponentsTable({
    getPrefix: wordsPage.getPrefix,
    markModified: storage.markModified,
    renderWordsDetails: wordTable.renderWordsDetails,
});

initWordCrud({
    config,
    getPrefix: wordsPage.getPrefix,
    storage,
    markModified: storage.markModified,
    renderWordsTable: wordTable.renderWordsTable,
    renderWordsDetails: wordTable.renderWordsDetails,
    fetchWordGroups: wordsPage.fetchWordGroups,
    getNahwuController: () => nahwuFormController,
    getCurrentCompareResult: wordsPage.getCurrentCompareResult,
    compareAnswers: wordsPage.compareAnswers,
    highlightErrors: wordsPage.highlightErrors,
});

document.addEventListener("DOMContentLoaded", () => {
    nahwuFormController = createNahwuFormController();
    wordsPage.boot();
});
