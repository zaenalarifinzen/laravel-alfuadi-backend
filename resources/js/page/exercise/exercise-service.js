/* ==========================================================================
   Exercise Service — logic murni seputar data soal:
   membangun URL, validasi & parsing response, caching localStorage,
   dan penyusunan payload jawaban. Tidak ada state internal di sini;
   semua di-passing lewat parameter supaya gampang di-test terpisah.
   ========================================================================== */

// ---------------------------------------------------------------------------
// URL building
// ---------------------------------------------------------------------------

export function buildExerciseUrl(
    config,
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
// Response validation / parsing
// ---------------------------------------------------------------------------

export function isValidExerciseContent(content) {
    return (
        !!content &&
        Array.isArray(content.wordGroups?.[0]?.words) &&
        content.wordGroups[0].words.length > 0
    );
}

export function resolveOrderNumber(exerciseData) {
    return exerciseData.display_order
        ? exerciseData.display_order
        : exerciseData.verse_id;
}

// ---------------------------------------------------------------------------
// Cache handling
// ---------------------------------------------------------------------------

export function getCachedExercise(storage, cacheKey) {
    const cachedKey = storage.getActiveStorageKey(cacheKey);
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

export function isCacheStillValid(cachedData, freshContent) {
    const cachedWords = cachedData?.wordGroups?.[0]?.words;
    const freshWords = freshContent?.wordGroups?.[0]?.words;

    if (!cachedWords?.length || !freshWords?.length) return false;

    return cachedWords[0].updated_at === freshWords[0].updated_at;
}

export function clearExerciseStorage() {
    Object.keys(localStorage)
        .filter((k) => k.startsWith("ex_"))
        .forEach((k) => localStorage.removeItem(k));
}

// ---------------------------------------------------------------------------
// Answer payload building
// ---------------------------------------------------------------------------

export function buildAnswerPayload(content, exerciseData, orderNumber) {
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

export function stripAnswerFields(wordGroups) {
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