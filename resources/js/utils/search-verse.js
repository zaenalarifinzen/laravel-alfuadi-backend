export function initSearchVerse({
    elements,
    getPrefix,
    isModified,
    showEditConfirmation,
    fetchWordGroups,
}) {
    let verseCount = 0;

    function fetchSurahList() {
        $.ajax({
            url: "/api/surahs",
            type: "GET",
            success: function (response) {
                response.data.forEach((surah) => {
                    elements.surahOption.insertAdjacentHTML(
                        "beforeend",
                        `<option value="${surah.id}" data-verse-count="${surah.verse_count}">${surah.id}. ${surah.name}</option>`,
                    );
                });
            },
        });
    }

    function updateVerseCount() {
        const selected =
            elements.surahOption.options[elements.surahOption.selectedIndex];
        verseCount = selected ? selected.getAttribute("data-verse-count") : 0;
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
        if (parseInt(elements.verseOption.value) < 1) {
            elements.verseOption.value = 1;
        }
        if (parseInt(elements.verseOption.value) > verseCount) {
            elements.verseOption.value = verseCount;
        }
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
