// =============================
// GLOBAL STATE & NON-DOM UTIL
// =============================

// tracking metadata 
let wordGroupsState = {
    initialIds: [],
    mergedMap: {},
    deletedIds: [],
    editedGroups: [],
    modified: false,
}

// =============================
// MAIN: all DOM related code
// =============================
document.addEventListener('DOMContentLoaded', function () {
    // =============================
    // VARIABEL GLOBAL (DOM)
    // =============================
    const btnUnselect = document.getElementById('btn-unselect');
    const btnEdit = document.getElementById('btn-edit');
    const btnMerge = document.getElementById('btn-merge');
    const btnSplit = document.getElementById('btn-split');

    const mergeForm = document.getElementById('merge-form');

    const splitIdInput = document.getElementById('split-id');
    const splitForm = document.getElementById('split-form');

    const wordgroupList = document.getElementById('wordgroup-list');
    const isPersisted = document.getElementById('is-persisted');

    let modified = false;

    // =============================
    // UTILITY (DOM-dependent)
    // =============================

    // Get new checkbox from DOM
    function getCheckboxes() {
        return document.querySelectorAll('.row-checkbox');
    }

    // =============================
    // MANAGEMEN CHECKBOX
    // =============================
    function updatebtnMerge() {
        const checkboxes = getCheckboxes();
        const checkedCount = Array.from(checkboxes).filter(x => x.checked).length;

        if (checkedCount >= 2) {
            btnMerge.classList.remove('disabled');
            btnSplit.disabled = false;
        } else {
            btnMerge.classList.add('disabled');
            btnSplit.disabled = true;
        }
    }

    function updatebtnEditAndSplit() {
        const checkboxes = getCheckboxes();
        const checked = Array.from(checkboxes).filter(x => x.checked);
        if (checked.length === 1) {
            btnEdit.classList.remove('disabled');
            btnSplit.classList.remove('disabled');
            btnEdit.disabled = false;
            btnSplit.disabled = false;
            splitIdInput.value = checked[0].value;
        } else {
            btnEdit.classList.add('disabled');
            btnSplit.classList.add('disabled');
            btnEdit.disabled = true;
            btnSplit.disabled = true;
            splitIdInput.value = '';
        }
    }

    // Event Listener Binding
    function bindCheckboxEvents() {
        const checkboxes = getCheckboxes();
        checkboxes.forEach(cb => cb.addEventListener('change', () => {
            updatebtnMerge();
            updatebtnEditAndSplit();
        }));
    }

    // Update Label
    function updateTitle(title) {
        const groupingTitle = document.getElementById('grouping-title');
        if (!groupingTitle) return;

        groupingTitle.textContent = title;
    }

    // =============================
    // FUNGSI FETCH WORD GROUPS
    // =============================
    function fetchWordGroups(surah_id, verse_number, verse_id) {
        let url;

        if (verse_id) {
            url = WORDGROUP_GET_URL.replace(':id', verse_id);
        } else if (surah_id && verse_number) {
            url = WORDGROUP_GET_URL.replace('/:id',
                `?surah_id=${surah_id}&verse_number=${verse_number}`);
        } else {
            alert('Parameter tidak lengkap');
            return
        }

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {

                const verseId = response.data.verse.id;
                currentVerseNumber.value = response.data.verse.number;
                currentSurahId.value = response.data.surah.id;

                currentVerseId.value = verseId;
                // isPersisted.value = response.data.isPersisted;
                const isPersisted = response.data.isPersisted;
                const storageKey = `grouping_${verseId}`;
                const wordGroupIds = response.data.wordGroups.map(wg => wg.id);

                // Reset state
                wordGroupsState = {
                    // Store the initial IDs of the word groups
                    initialIds: wordGroupIds,
                    deletedIds: [],
                    mergedMap: {},
                    editedGroups: [],
                    modified: false,
                }

                // Clear old cache
                Object.keys(localStorage)
                    .filter(k => k.startsWith('grouping_'))
                    .forEach(k => localStorage.removeItem(k));

                // Save to local storage
                localStorage.setItem(storageKey, JSON.stringify({
                    ...response,
                    initialIds: wordGroupIds,
                    deletedIds: [],
                    mergedMap: {},
                    modified: false
                }));

                // Show in html
                updateResultLabel(response.data.surah, response.data.verse);
                renderWordGroups(response.data)

                // Update button complete
                const btnLabel = isPersisted ? 'Update' : 'Simpan & Lanjutkan';
                btnComplete.textContent = btnLabel;

                // Update URL in address bar
                history.pushState({}, '', `?verse_id=${verseId}`);

                bindCheckboxEvents();
                updatebtnMerge();
                updatebtnEditAndSplit();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                iziToast.error({
                    message: 'Terjadi kesalahan',
                    position: 'topRight'
                });
            }
        })
    }

    /**
     * SYNC UI TO LOCAL STORAGE WORDGROUP
     */
    // function syncLocalStorageWordGroups() {
    //     const verseId = currentVerseId.value;
    //     const storageKey = `grouping_${verseId}`;
    //     let stored = JSON.parse(localStorage.getItem(storageKey));

    //     if (!stored || !stored.data) return;

    //     const items = Array.from(document.querySelectorAll('#wordgroup-list .selectgroup-item'));
    //     stored.modified = true;

    //     stored.data.wordGroups = items.map((item, index) => {
    //         return {
    //             id: item.querySelector('.selectgroup-input').value,
    //             text: item.querySelector('.selectgroup-button').textContent.trim(),
    //             order_number: index + 1,
    //         }
    //     });

    //     localStorage.setItem(storageKey, JSON.stringify(stored));
    // }

    // =============================
    // RENDER WORDGROUPS
    // =============================
    // function renderWordGroups(data) {
    //     const wordGroups = data.wordGroups || [];

    //     if (wordGroups.length > 0) {
    //         let html = '';
    //         wordGroups.forEach(wordGroup => {
    //             html += `
    //                 <label class="selectgroup-item arabic-pill">
    //                     <input type="checkbox" name="ids[]" value="${wordGroup.id}" class="selectgroup-input row-checkbox">
    //                     <span class="selectgroup-button arabic-text ar-title">${wordGroup.text}</span>
    //                 </label>
    //             `;
    //         });

    //         wordgroupList.innerHTML = html;
    //         const firstGroup = wordGroups[0];
    //         const editorName = firstGroup.editor_info ? firstGroup.editor_info.name : ' -';
    //         $('.editor a').contents().last()[0].textContent = ` ${editorName}`;
    //     }
    // }

    // =============================
    // TOOLS
    // =============================
    function handleMergeSubmit(e) {
        e.preventDefault();

        const checkboxes = getCheckboxes();
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

        if (btnMerge.classList.contains('disabled')) {
            e.preventDefault();
            return;
        }

        if (selectedCheckboxes.length < 2) {
            e.preventDefault();
            alert('Pilih minimal 2 baris untuk digabungkan');
            return;
        }

        // order check
        const selectedIndexes = Array.from(checkboxes)
            .map((cb, i) => cb.checked ? i : null)
            .filter(i => i !== null);

        const isSequential = selectedIndexes.every((val, i, arr) =>
            i === 0 || val - arr[i - 1] === 1
        );

        if (!isSequential) {
            e.preventDefault();
            iziToast.warning({
                message: 'Kalimat harus berurutan dan tidak boleh lompat',
                position: 'topRight'
            });
            return;
        }

        // Merge text
        const selectedText = selectedCheckboxes.map(cb => {
            const btn = cb.closest('.selectgroup-item').querySelector('.selectgroup-button');
            return btn.textContent.trim();
        });
        const combinedText = selectedText.join(' ');

        const firstLabel = selectedCheckboxes[0].closest('.selectgroup-item');
        const newLabel = firstLabel.cloneNode(true);

        const newInput = newLabel.querySelector('.selectgroup-input');
        const newButton = newLabel.querySelector('.selectgroup-button');

        newInput.checked = false;
        newButton.textContent = combinedText;

        // Insert merge result after first
        wordgroupList.insertBefore(newLabel, firstLabel);
        const newOrder = Array.from(wordgroupList.children).indexOf(newLabel) + 1;

        // Delete related labels
        selectedCheckboxes.forEach(cb => cb.closest('.selectgroup-item').remove());

        // Track merging
        const mergedIds = selectedCheckboxes.map(cb => cb.value);
        const targetId = selectedCheckboxes[0].value;

        mergedIds.slice(1).forEach(id => {
            if (wordGroupsState.initialIds.includes(Number(id))) {
                wordGroupsState.mergedMap[id] = targetId;
                wordGroupsState.deletedIds.push(id);
            }
        });

        wordGroupsState.modified = true;

        // Re save to local storage
        // const verseId = currentVerseId.value;
        // const storageKey = `grouping_${verseId}`;
        // let stored = JSON.parse(localStorage.getItem(storageKey));

        // stored.deletedIds = wordGroupsState.deletedIds;
        // stored.mergedMap = wordGroupsState.mergedMap;

        // localStorage.setItem(storageKey, JSON.stringify(stored));
        // syncLocalStorageWordGroups();

        // Re-bind event to new element
        bindCheckboxEvents();
        updatebtnMerge();
        updatebtnEditAndSplit();
    }

    // =============================
    // FUNGSI UNTUK SPLIT
    // =============================
    function handleSplitSubmit(e) {
        e.preventDefault();

        const checkboxes = getCheckboxes();
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

        if (btnSplit.classList.contains('disabled')) {
            e.preventDefault();
            return;
        }

        if (selectedCheckboxes.length !== 1) {
            alert('Pilih 1 kalimah untuk dipisah');
            return;
        }

        const selectedCheckbox = selectedCheckboxes[0];
        const label = selectedCheckbox.closest('.selectgroup-item');
        const textButton = label.querySelector('.selectgroup-button');
        const originalText = textButton.textContent.trim();

        const words = originalText.split(' ').filter(w => w.trim() !== '');
        if (words.length <= 1) {
            iziToast.warning({
                message: 'Tidak bisa memisah 1 kalimat',
                position: 'topRight'
            });
            return;
        }

        const firstWord = words[0];
        textButton.textContent = firstWord;

        let insertAfter = label;

        // Add new element each new word
        for (let i = 1; i < words.length; i++) {
            const word = words[i];

            const newLabel = label.cloneNode(true);
            const newInput = newLabel.querySelector('.selectgroup-input');
            const newButton = newLabel.querySelector('.selectgroup-button');

            newInput.checked = false;
            newInput.value = `S-${Math.floor(Math.random(1) * 1000000)}`;
            newButton.textContent = word;

            // Insert before old label
            wordgroupList.insertBefore(newLabel, insertAfter.nextSibling);
            insertAfter = newLabel;

        }

        wordGroupsState.modified = true;

        // Re-save to local storage
        // syncLocalStorageWordGroups();

        // Re-bind event to new element
        bindCheckboxEvents();
        updatebtnMerge();
        updatebtnEditAndSplit();
    }

    // =============================
    // EDIT WORDGROUP TEXT
    // =============================
    async function handleEditSubmit(e) {
        e.preventDefault();

        const checkboxes = getCheckboxes();
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

        if (btnEdit.classList.contains('disabled')) {
            e.preventDefault();
            return;
        }

        if (selectedCheckboxes.length !== 1) {
            alert('Pilih 1 kalimah untuk diedit');
            return;
        }

        const selectedCheckbox = selectedCheckboxes[0];
        const label = selectedCheckbox.closest('.selectgroup-item');
        const textButton = label.querySelector('.selectgroup-button');
        const currentText = `  ${textButton.textContent}  `;

        const newText = await swal({
            title: 'Edit Kalimat',
            content: {
                element: 'input',
                attributes: {
                    className: 'swal-content__input arabic-text',
                    placeholder: 'Masukkan teks',
                    value: currentText,
                    type: 'text',

                },
            },
            buttons: {
                cancel: {
                    text: 'Batal',
                    visible: true,
                },
                confirm: {
                    text: 'Submit',
                    visible: true,
                    className: 'btn-success'
                }
            },
        });

        if (!newText) return;

        // Set new text to same element
        textButton.textContent = newText.trim();

        wordGroupsState.modified = true;

        // Re-save to local storage
        syncLocalStorageWordGroups();

        // Re-bind / update state
        bindCheckboxEvents();
        updatebtnMerge();
        updatebtnEditAndSplit();
    }

    // =============================
    // CONFIRMATION DIALOG
    // =============================
    function showEditConfirmation() {
        return swal({
            icon: 'warning',
            title: 'Perubahan belum disimpan',
            text: 'Abaikan perubahan yang sudah ada?',
            buttons: {
                cancel: {
                    text: 'Kembali',
                    visible: true,
                },
                confirm: {
                    text: 'Abaikan',
                    visible: true,
                    className: 'btn-success'
                }
            },
        });
    }
    
    // =============================
    // INISIALISASI EVENT LISTENER
    // =============================

    // Event listener for checkbox
    bindCheckboxEvents();

    // Event listener for unselect button
    btnUnselect.addEventListener('click', function () {
        const checkboxes = getCheckboxes();
        checkboxes.forEach(cb => cb.checked = false);
        updatebtnMerge();
        updatebtnEditAndSplit();
    });

    // Event listener for form edit
    btnEdit.addEventListener('click', handleEditSubmit);

    // Event listener for form merge
    mergeForm.addEventListener('submit', handleMergeSubmit);

    // Event listener for form split
    splitForm.addEventListener('submit', handleSplitSubmit);

    // Event listener for update
    const completeForm = document.getElementById('complete-form');
    if (completeForm) {
        completeForm?.addEventListener('submit', function (e) {
            e.preventDefault();

            const groups = Array.from(document.querySelectorAll('#wordgroup-list .selectgroup-item'))
                .map((item) => ({
                    id: item.querySelector('.selectgroup-input').value,
                    text: item.querySelector('.selectgroup-button').textContent.trim(),
                }));

            fetch(completeForm.action, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ groups }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) {
                        throw new Error(data.message || 'Gagal memperbarui exercise.');
                    }

                    wordGroupsState.modified = false;
                    iziToast.success({
                        message: data.message,
                        position: 'topRight',
                    });
                })
                .catch((error) => {
                    console.error('Error:', error);
                    swal('Error', error.message || 'Gagal menyimpan grouping.', 'error');
                });
        });
    }

    // Event listener for floating button
    const buttonBar = document.getElementById('button-bar');
    const sentinel = document.getElementById('button-bar-sentinel'); // [ADDED]

    if (buttonBar && sentinel) {
        const observer = new IntersectionObserver(entries => {
            const entry = entries[0];
            if (!entry.isIntersecting) {
                buttonBar.classList.add('floating');
            } else {
                buttonBar.classList.remove('floating');
            }
        }, {
            threshold: 0.1
        });

        observer.observe(sentinel);
    }


    // =============================
    // INITIALIZE
    // =============================
    updatebtnMerge();
    updatebtnEditAndSplit();

});