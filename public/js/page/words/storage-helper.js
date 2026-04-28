// ==========================
// STORAGE HELPER
// ==========================

// get active key from storage
function getActiveStorageKey(prefix) {
    return Object.keys(localStorage).find(k => k.startsWith(prefix)) || null;
}

// get data from storage
function getStoredData(prefix) {
    const key = getActiveStorageKey(prefix);
    if (!key) return null;

    const data = JSON.parse(localStorage.getItem(key))
    return {key, data};
}

// set modified
function markModified(prefix) {
    const key = getActiveStorageKey(prefix);
    if (!key) return;

    const stored = JSON.parse(localStorage.getItem(key));

    stored.modified = true;
    localStorage.setItem(key, JSON.stringify(stored));
}

// check is modified
function isModified(prefix) {
    const stored = getStoredData(prefix);
    return stored?.data?.modified === true;
}

// reset modified mark (set false)
function resetModified(prefix) {
    const key = getActiveStorageKey(prefix);
    if (!key) return;

    const stored = JSON.parse(localStorage.getItem(key));
    stored.modified = false;
    localStorage.setItem(key, JSON.stringify(stored));
}