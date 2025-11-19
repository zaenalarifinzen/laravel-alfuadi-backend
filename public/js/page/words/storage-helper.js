// ==========================
// STORAGE HELPER
// ==========================

// get active key from storage
function getActiveStorageKey() {
    return Object.keys(localStorage).find(k => k.startsWith('wordgroups_')) || null;
}

// get data from storage
function getStoredData() {
    const key = getActiveStorageKey();
    if (!key) return null;

    const data = JSON.parse(localStorage.getItem(key))
    return {key, data};
}

// set modified
function markModified() {
    const key = getActiveStorageKey();
    if (!key) return;

    const stored = JSON.parse(localStorage.getItem(key));

    stored.modified = true;
    localStorage.setItem(key, JSON.stringify(stored));
}

// check is modified
function isModified() {
    const stored = getStoredData();
    return stored?.data?.modified === true;
}

// reset modified mark (set false)
function resetModified() {
    const key = getActiveStorageKey();
    if (!key) return;

    const stored = JSON.parse(localStorage.getItem(key));
    stored.modified = false;
    localStorage.setItem(key, JSON.stringify(stored));
}