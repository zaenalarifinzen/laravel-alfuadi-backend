// ==========================
// STORAGE HELPER
// ==========================

export function getActiveStorageKey(prefix) {
    return Object.keys(localStorage).find((k) => k.startsWith(prefix)) ?? null;
}
window.getActiveStorageKey = getActiveStorageKey;

export function getStoredObject(prefix) {
    const key = getActiveStorageKey(prefix);    
    if (!key) return null;

    const raw = localStorage.getItem(key);
    if (raw === null) return null;

    try {
        return JSON.parse(raw);
    } catch (error) {
        console.warn(`Invalid JSON in localStorage for key ${key}:`, error);
        return null;
    }
}
window.getStoredObject = getStoredObject;

export function getStoredData(prefix) {
    return getStoredObject(prefix);
}
window.getStoredData = getStoredData;

export function updateStoredData(prefix, updater) {
    const key = getActiveStorageKey(prefix);
    if (!key) return null;

    const stored = getStoredObject(prefix);
    if (!stored) return null;

    if (typeof updater === "function") {
        updater(stored);
    } else if (typeof updater === "object" && updater !== null) {
        Object.assign(stored, updater);
    } else {
        return null;
    }

    localStorage.setItem(key, JSON.stringify(stored));
    return stored;
}
window.updateStoredData = updateStoredData;

export function markModified(prefix) {
    updateStoredData(prefix, (stored) => {
        stored.modified = true;
    });    
}
window.markModified = markModified;

export function isModified(prefix) {
    return getStoredData(prefix)?.modified === true;
}
window.isModified = isModified;

export function resetModified(prefix) {
    updateStoredData(prefix, (stored) => {
        stored.modified = false;
    });
}
window.resetModified = resetModified;
