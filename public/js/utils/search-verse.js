const surahOption = document.getElementById("surah-option");
const verseOption = document.getElementById("verse-option");
const searchForm = document.getElementById("search-verse-form");

// =============================
// FETCH SURAH LIST
// =============================
function fetchSurahList() {
    $.ajax({
        url: "/api/surahs",
        type: "GET",
        success: function (response) {
            response.data.forEach((surah) => {
                surahOption.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${surah.id}" data-verse-count="${surah.verse_count}">${surah.id}. ${surah.name}</option>`,
                );
            });
        },
    });
}

function updateVerseCount() {
    const selected = surahOption.options[surahOption.selectedIndex];
    verseCount = selected ? selected.getAttribute("data-verse-count") : 0;
}

// =============================
// SEARCH VERSE
// =============================
async function searchVerse(e) {
    e.preventDefault();

    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    fetchWordGroups(surahOption.value, verseOption.value);
}

surahOption.addEventListener("change", () => {
    updateVerseCount();
    verseOption.value = 1;
});

verseOption.addEventListener("change", () => {
    if (parseInt(verseOption.value) < 1) {
        verseOption.value = 1;
    }
    if (parseInt(verseOption.value) > verseCount)
        verseOption.value = verseCount;
});

searchForm.addEventListener("submit", searchVerse);

// =============================
// VERSE NAVIGATOR
// =============================
const btnPrev = document.getElementById("btn-prev-verse");
const btnNext = document.getElementById("btn-next-verse");

async function goToPrevVerse() {
    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    let id = parseInt(currentVerseId.value);
    if (id > 1) fetchWordGroups(null, null, id - 1);
}

async function goToNextVerse() {
    const modified = isModified(wordGroupsPrefix);
    if (modified) {
        const confirmed = await showEditConfirmation();
        if (!confirmed) return;
    }

    let id = parseInt(currentVerseId.value);
    if (id < 6236) fetchWordGroups(null, null, id + 1);
}

btnPrev.addEventListener("click", goToPrevVerse);
btnNext.addEventListener("click", goToNextVerse);