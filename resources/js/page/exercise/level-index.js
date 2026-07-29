import * as storage from "../../utils/storage-helper";

document.addEventListener("DOMContentLoaded", () => {
    const STORAGE_PREFIX = "answer_user_";

    const lastOpenedEl = document.getElementById("last-opened-info");
    const labelEl = document.getElementById("info-label");

    if (!lastOpenedEl || !labelEl) return;

    function getLastExerciseData() {
        try {
            const key = storage.getActiveStorageKey(STORAGE_PREFIX);
            if (!key) return null;

            const raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function renderLastOpened(data) {
        const isVisible = !!(data?.surah?.name && data?.verse?.number);

        lastOpenedEl.style.display = isVisible ? "inline-block" : "none";

        if (isVisible) {
            labelEl.textContent = `${data.surah.name} ayat ${data.verse.number}`;
        }
    }

    const cachedData = getLastExerciseData();
    renderLastOpened(cachedData);
});
