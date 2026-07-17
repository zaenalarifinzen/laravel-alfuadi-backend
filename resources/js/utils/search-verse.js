export function initSearchVerse({
    elements,
    getPrefix,
    isModified,
    showEditConfirmation,
    fetchWordGroups,
    config = {},
}) {
    let verseCount = 0;

    function getAllowedSurahIds() {
        if (!Array.isArray(config.allowedSurahIds)) {
            return [];
        }

        return config.allowedSurahIds
            .map((value) => Number(value))
            .filter((value) => Number.isInteger(value) && value > 0);
    }

    function getAllowedVersesForSurah(surahId) {
        const bySurah = config.allowedVerseNumbersBySurah || {};
        const allowed = bySurah[surahId] ?? bySurah[String(surahId)] ?? null;

        if (!Array.isArray(allowed)) {
            return null;
        }

        return allowed
            .map((value) => Number(value))
            .filter((value) => Number.isInteger(value) && value > 0);
    }

    function isSurahAllowed(surahId) {
        const allowedSurahIds = getAllowedSurahIds();
        if (allowedSurahIds.length === 0) {
            return true;
        }

        return allowedSurahIds.includes(Number(surahId));
    }

    function fetchSurahList() {
        $.ajax({
            url: "/api/surahs",
            type: "GET",
            success: function (response) {
                response.data.forEach((surah) => {
                    if (!isSurahAllowed(surah.id)) {
                        return;
                    }

                    const allowedVerses = getAllowedVersesForSurah(surah.id);
                    const verseCountValue = allowedVerses ? allowedVerses.length : surah.verse_count;
                    elements.surahOption.insertAdjacentHTML(
                        "beforeend",
                        `<option value="${surah.id}" data-verse-count="${verseCountValue}" data-allowed-verses="${allowedVerses ? allowedVerses.join(',') : ''}">${surah.id}. ${surah.name}</option>`,
                    );
                });
            },
        });
    }

    function updateVerseCount() {
        const selected =
            elements.surahOption.options[elements.surahOption.selectedIndex];
        const allowedVerses = selected
            ? selected.getAttribute("data-allowed-verses")
            : "";
        const verseNumbers = allowedVerses
            ? allowedVerses.split(",").filter(Boolean).map(Number)
            : [];

        verseCount = selected ? Number(selected.getAttribute("data-verse-count") || 0) : 0;
        elements.verseOption.setAttribute("min", "1");
        elements.verseOption.setAttribute("max", verseCount || 1);

        if (verseNumbers.length > 0) {
            elements.verseOption.setAttribute("data-allowed-verses", verseNumbers.join(","));
            elements.verseOption.setAttribute("max", String(Math.max(...verseNumbers)));
            elements.verseOption.setAttribute("min", String(Math.min(...verseNumbers)));
        } else {
            elements.verseOption.removeAttribute("data-allowed-verses");
        }
    }

    function normalizeVerseNumber() {
        const selected =
            elements.surahOption.options[elements.surahOption.selectedIndex];
        const allowedVerses = selected
            ? selected.getAttribute("data-allowed-verses")
            : "";
        const verseNumbers = allowedVerses
            ? allowedVerses.split(",").filter(Boolean).map(Number)
            : [];

        const value = parseInt(elements.verseOption.value, 10);
        if (Number.isNaN(value)) {
            elements.verseOption.value = verseNumbers.length > 0 ? verseNumbers[0] : 1;
            return;
        }

        if (verseNumbers.length > 0 && !verseNumbers.includes(value)) {
            elements.verseOption.value = verseNumbers[0];
            return;
        }

        if (value < 1) {
            elements.verseOption.value = 1;
        }

        if (verseNumbers.length === 0 && value > verseCount) {
            elements.verseOption.value = verseCount;
        }
    }

    async function searchVerse(e) {
        e.preventDefault();

        const prefix = getPrefix();
        const modified = isModified(prefix);
        if (modified) {
            const confirmed = await showEditConfirmation();
            if (!confirmed) return;
        }

        fetchWordGroups(elements.surahOption.value, elements.verseOption.value);
    }

    elements.surahOption.addEventListener("change", () => {
        updateVerseCount();
        elements.verseOption.value = 1;
    });

    elements.verseOption.addEventListener("change", () => {
        normalizeVerseNumber();
    });

    elements.searchForm.addEventListener("submit", searchVerse);

    async function goToPrevVerse() {
        const prefix = getPrefix();
        const modified = isModified(prefix);
        if (modified) {
            const confirmed = await showEditConfirmation();
            if (!confirmed) return;
        }

        const id = parseInt(elements.currentVerseId.value);
        if (id > 1) fetchWordGroups(null, null, id - 1);
    }

    async function goToNextVerse() {
        const prefix = getPrefix();
        const modified = isModified(prefix);
        if (modified) {
            const confirmed = await showEditConfirmation();
            if (!confirmed) return;
        }

        const id = parseInt(elements.currentVerseId.value);
        if (id < 6236) fetchWordGroups(null, null, id + 1);
    }

    elements.btnPrevVerse.addEventListener("click", goToPrevVerse);
    elements.btnNextVerse.addEventListener("click", goToNextVerse);

    return {
        fetchSurahList,
        updateVerseCount,
    };
}
