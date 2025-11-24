document.addEventListener('DOMContentLoaded', function () {
    // =============================
    // VARIABEL GLOBAL
    // =============================
    const btnUnselect = document.getElementById('btn-unselect');
    const btnEdit = document.getElementById('btn-edit');
    const btnMerge = document.getElementById('btn-merge');
    const btnSplit = document.getElementById('btn-split');
    const btnPrev = document.getElementById('btn-prev-verse');
    const btnNext = document.getElementById('btn-next-verse');
    const btnComplete = document.getElementById('btn-complete');
    const btnTestSave = document.getElementById('btn-test-save');

    const splitIdInput = document.getElementById('split-id');

    const filterForm = document.getElementById('filter-form');
    const mergeForm = document.getElementById('merge-form');
    const splitForm = document.getElementById('split-form');

    const surahOption = document.getElementById('surah-option');
    const verseOption = document.getElementById('verse-option');
    const wordgroupList = document.getElementById('wordgroup-list');
    const currentVerseId = document.getElementById('verse-id');
    const currentSurahId = document.getElementById('surah-id');
    const currentVerseNumber = document.getElementById('verse-number');

    const resultLabel = document.getElementById('result-verse');
    const errorMsg = document.getElementById('merge-error');
    let modified = false;
    let isPersisted = document.getElementById('is-persisted').value;
    let verseCount;

    // =============================
    // INISIALISASI TRACKING PERUBAHAN
    // =============================

    // metadata untuk tracking perubahan
    let wordGroupsState = {
        initialIds: [],
        mergedMap: {},
        deletedIds: [],
        modified: false,
    };

    // =============================
    // FUNGSI UTILITAS
    // =============================

    // Ambil checkbox terbaru dari DOM
    function getCheckboxes() {
        return document.querySelectorAll('.row-checkbox');
    }

    function showError(message) {
        errorMsg.textContent = message;
        errorMsg.style.display = 'block';
        setTimeout(() => {
            errorMsg.style.display = 'none';
        }, 3000);
    }

    // =============================
    // FUNGSI MANAJEMEN CHECKBOX
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

    // Pasang event listener ke semua checkbox (baik awal maupun setelah fetch)
    function bindCheckboxEvents() {
        const checkboxes = getCheckboxes();
        checkboxes.forEach(cb => cb.addEventListener('change', () => {
            updatebtnMerge();
            updatebtnEditAndSplit();
        }));
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
                const storageKey = `grouping_${verseId}`;
                const wordGroupIds = response.data.wordGroups.map(wg => wg.id);

                // Reset state
                wordGroupsState = {
                    // Store the initial IDs of the word groups
                    initialIds: wordGroupIds,
                    deletedIds: [],
                    mergedMap: {},
                    modified: false,
                }

                // Clear old chache
                Object.keys(localStorage)
                    .filter(k => k.startsWith('grouping_'))
                    .forEach(k => localStorage.removeItem(k));

                // Save to local storage
                const initialIds = response.data.wordGroups.id;
                localStorage.setItem(storageKey, JSON.stringify({
                    ...response,
                    initialIds: wordGroupIds,
                    deletedIds: [],
                    mergedMap: {},
                    modified: false
                }));

                // Show in html
                renderWordGroups(response.data)

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

    // =============================
    // SYNC LOCAL STRORAGE WORDGROUP
    // =============================
    function syncLocalStorageWordGroups() {
        const verseId = currentVerseId.value;
        const storageKey = `grouping_${verseId}`;
        let stored = JSON.parse(localStorage.getItem(storageKey));

        if (!stored || !stored.data) return;

        const items = Array.from(document.querySelectorAll('#wordgroup-list .selectgroup-item'));

        stored.data.wordGroups = items.map((item, index) => {
            return {
                id: item.querySelector('.selectgroup-input').value,
                text: item.querySelector('.selectgroup-button').textContent.trim(),
                order_number: index + 1,
            }
        });

        localStorage.setItem(storageKey, JSON.stringify(stored));
    }

    // =============================
    // RENDER WORDGROUPS
    // =============================
    function renderWordGroups(data) {
        const wordGroups = data.wordGroups || [];

        if (wordGroups.length > 0) {
            let html = '';
            wordGroups.forEach(wordGroup => {
                html += `
                    <label class="selectgroup-item arabic-pill">
                        <input type="checkbox" name="ids[]" value="${wordGroup.id}" class="selectgroup-input row-checkbox">
                        <span class="selectgroup-button arabic-text ar-title">${wordGroup.text}</span>
                    </label>
                `;
            });

            wordgroupList.innerHTML = html;
            const firstGroup = wordGroups[0];
            const editorName = firstGroup.editor_info ? firstGroup.editor_info.name : ' -';
            $('.editor a').contents().last()[0].textContent = ` ${editorName}`;
        }
    }

    // =============================
    // FUNGSI UNTUK MERGE
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

        // CEK URUTAN — tidak boleh lompat
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

        // Gabungkan text
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

        // Sisipkan hasil merge sebelum label pertama
        wordgroupList.insertBefore(newLabel, firstLabel);
        const newOrder = Array.from(wordgroupList.children).indexOf(newLabel) + 1;

        // Hapus semua label yang tergabung
        selectedCheckboxes.forEach(cb => cb.closest('.selectgroup-item').remove());

        // Track penggabungan
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
        const verseId = currentVerseId.value;
        const storageKey = `grouping_${verseId}`;
        let stored = JSON.parse(localStorage.getItem(storageKey));

        stored.deletedIds = wordGroupsState.deletedIds;
        stored.mergedMap = wordGroupsState.mergedMap;
        stored.modified = true;

        localStorage.setItem(storageKey, JSON.stringify(stored));
        syncLocalStorageWordGroups();

        // Re-bind event ke elemen baru
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

        // Reset pesan error
        errorMsg.style.display = 'none';
        errorMsg.textContent = '';

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

        wordGroupsState.editedGroups.push({
            id: selectedCheckbox.value,
            text: firstWord,
            order_number: Array.from(wordgroupList.children).indexOf(label) + 1,
            note: 'split-edit'
        });

        let insertAfter = label;

        // Tambahkan elemen baru untuk setiap kata
        for (let i = 1; i < words.length; i++) {
            const word = words[i];

            const newLabel = label.cloneNode(true);
            const newInput = newLabel.querySelector('.selectgroup-input');
            const newButton = newLabel.querySelector('.selectgroup-button');

            newInput.checked = false;
            newInput.value =
                `S${Math.random().toString(36)}`;
            newButton.textContent = word;

            // Sisipkan sebelum label lama agar urutan tetap benar
            wordgroupList.insertBefore(newLabel, insertAfter.nextSibling);
            insertAfter = newLabel;

            // Tandai yang baru
            wordGroupsState.newGroups.push({
                text: word.trim(),
                order_number: Array.from(wordgroupList.children).indexOf(newLabel) + 1,
                note: 'split-from' + selectedCheckbox.value
            });
        }

        modified = true;

        // Re-bind event ke elemen baru
        bindCheckboxEvents();
        updatebtnMerge();
        updatebtnEditAndSplit();
    }

    // =============================
    // FUNGSI UNTUK EDIT KALIMAT
    // =============================

    async function handleEditSubmit(e) {
        e.preventDefault();

        const checkboxes = getCheckboxes();
        const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

        // Reset pesan error
        errorMsg.style.display = 'none';
        errorMsg.textContent = '';

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

        // Set teks baru pada elemen yang sama
        textButton.textContent = newText.trim();

        // Track perubahan
        const existing = wordGroupsState.editedGroups.find(g => g.id === selectedCheckbox.value);
        if (existing) {
            existing.text = newText.trim();
        } else {
            wordGroupsState.editedGroups.push({
                id: selectedCheckbox.value,
                order_number: Array.from(wordgroupList.children).indexOf(label) + 1,
                text: newText.trim(),
                note: 'edited'
            });
        }

        modified = true;

        // Re-bind / update state
        bindCheckboxEvents();
        updatebtnMerge();
        updatebtnEditAndSplit();
    }

    function updateResultText(surahName, verseNumber) {
        const resultLabel = document.getElementById('result-verse');
        if (!resultLabel) return;

        if (surahName && verseNumber) {
            resultLabel.textContent = `${surahName} - Ayat ${verseNumber}`;
        } else if (surahName) {
            resultLabel.textContent = surahName;
        } else {
            resultLabel.textContent = 'Ayat Terpilih';
        }
    }


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

    function updateVerseCount() {
        const selected = surahOption.options[surahOption.selectedIndex];
        verseCount = selected ? selected.getAttribute('data-verse-count') : 0;

    }

    async function handleFilterSubmit(e) {
        e.preventDefault();

        if (modified) {
            const confirmed = await showEditConfirmation()
            if (!confirmed) return;
        };

        fetchWordGroups(surahOption.value, verseOption.value);
        modified = false;
    }

    // =============================
    // FUNGSI NAVIGASI AYAT
    // =============================

    async function goToPrevVerse() {
        if (modified) {
            const confirmed = await showEditConfirmation()
            if (!confirmed) return;
        };

        let verseId = parseInt(currentVerseId.value);

        if (verseId > 1) {
            currentVerseId.value = verseId - 1;
            fetchWordGroups(null, null, currentVerseId.value);
            modified = false;
        }
    }

    async function goToNextVerse() {
        if (modified) {
            const confirmed = await showEditConfirmation()
            if (!confirmed) return;
        };

        let verseId = parseInt(currentVerseId.value);
        const max = 6236;

        if (verseId < max) {
            currentVerseId.value = verseId + 1;
            fetchWordGroups(null, null, currentVerseId.value);
            modified = false;
        }
    }

    // =============================
    // FUNGSI SIMPAN WORDGROUP
    // =============================
    function save() {
        swal({
            title: 'Konfirmasi',
            text: 'Yakin ingin menyimpan ayat ini?',
            buttons: {
                cancel: {
                    text: 'Batal',
                    visible: true,
                },
                confirm: {
                    text: 'Simpan',
                    visible: true,
                    className: 'btn-success'
                },
            },
        }).then((willSave) => {
            if (!willSave) return;

            const groups = Array.from(document.querySelectorAll('.selectgroup-input')).map(input =>
            ({
                id: input.value,
                text: input.closest('.selectgroup-item').querySelector(
                    '.selectgroup-button')
                    .textContent.trim()
            }));

            fetch('/wordgroups/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute(
                            'content')
                },
                body: JSON.stringify({
                    surah_id: currentSurahId.value,
                    verse_number: currentVerseNumber.value,
                    verse_id: currentVerseId.value,
                    groups: groups
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nextVerseId = parseInt(currentVerseId.value) + 1;
                        window.location.href =
                            `/wordgroups/grouping?verse_id=${nextVerseId}`;
                    } else {
                        swal('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    swal('Error', 'Gagal menyimpan data.', 'error');
                });
        });
    }

    // =============================
    // FUNGSI UPDATE WORDGROUP
    // =============================
    function update() {
        swal({
            title: 'Konfirmasi',
            text: 'Yakin ingin mengupdate ayat ini?',
            buttons: {
                cancel: {
                    text: 'Batal',
                    visible: true,
                },
                confirm: {
                    text: 'Update',
                    visible: true,
                    className: 'btn-success'
                },
            },
        }).then((willSave) => {
            if (!willSave) return;

            const verseId = currentVerseId.value;
            const storageKey = `grouping_${verseId}`;
            let stored = JSON.parse(localStorage.getItem(storageKey));

            const payload = {
                verse_id: currentVerseId.value,
                surah_id: currentSurahId.value,
                verse_number: currentVerseNumber.value,
                edited_groups: stored.data.wordGroups,
                merged_map: stored.mergedMap,
                deleted_ids: stored.deletedIds,
            };

            console.log(payload);
            
            $.ajax({
                url: "/wordgroups/multiple-update",
                method: "POST",
                data: JSON.stringify(payload),
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    nextVerseId = parseInt(currentVerseId.value) + 1;

                    // resetWordGroupState();
                    modified = false;

                    window.location.href =
                        `/wordgroups/grouping?verse_id=${nextVerseId}`;
                },
                error: function (xhr) {
                    console.error('❌ Error:', xhr.responseText);
                    alert('Gagal kirim data, periksa console.');
                }
            });
        });
    }

    // =============================
    // INISIALISASI EVENT LISTENER
    // =============================

    // Event listener untuk checkbox
    bindCheckboxEvents();

    // Event listener untuk tombol unselect
    btnUnselect.addEventListener('click', function () {
        const checkboxes = getCheckboxes();
        checkboxes.forEach(cb => cb.checked = false);
        updatebtnMerge();
        updatebtnEditAndSplit(); // Update split button juga

        // Reset pesan error
        errorMsg.style.display = 'none';
        errorMsg.textContent = '';
    });

    // Event listener untuk form merge
    btnEdit.addEventListener('click', handleEditSubmit);

    // Event listener untuk form merge
    mergeForm.addEventListener('submit', handleMergeSubmit);

    // Event listener untuk form split
    splitForm.addEventListener('submit', handleSplitSubmit);

    // Event listener untuk form filter
    filterForm.addEventListener('submit', handleFilterSubmit);

    // Event listener untuk perubahan surah
    surahOption.addEventListener('change', function () {
        updateVerseCount();
        verseOption.value = 1;
    });

    // Event listener untuk batasi jumlah ayat
    verseOption.addEventListener('change', function () {
        if (parseInt(verseOption.value) < 1) {
            verseOption.value = 1;
        }

        if (parseInt(verseOption.value) > verseCount) {
            verseOption.value = verseCount;
        }
    });

    // Event listener untuk simpan atau update
    const completeForm = document.getElementById('complete-form');
    if (completeForm) {
        completeForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isPersisted) {
                update();
            } else {
                save();
            }
        });
    }

    // Event listener untuk floating tombol
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

    // Event listener untuk navigasi ayat
    btnPrev.addEventListener('click', goToPrevVerse);
    btnNext.addEventListener('click', goToNextVerse);

    // =============================
    // INISIALISASI AWAL
    // =============================

    // Inisialisasi opsi ayat jika surah sudah dipilih
    if (surahOption.value) updateVerseCount();

    // Inisialisasi status tombol
    updatebtnMerge();
    updatebtnEditAndSplit();

});

function showLoading() {
    $('#loading-overlay').css({
        visibility: 'visible',
        opacity: '1'
    });
}

function hideLoading() {
    $('#loading-overlay').css({
        visibility: 'hidden',
        opacity: '0'
    });
}

$(document).ajaxStart(showLoading);
$(document).ajaxStop(hideLoading);