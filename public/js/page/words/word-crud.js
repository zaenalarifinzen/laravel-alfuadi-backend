"use strict";

// open modal
$('#btn-add-word').on('click', function () {
    $('#form-add-word')[0].reset();
    $('#input-id').val('');
    // $('#form-add-word-label').text('Tambah Kalimat');
    $('#submit-button').text('Tambahkan');

    $('#modal-add-word').modal('show');
});

$('#form-add-word').on('submit', function (e) {
    e.preventDefault();

    const wordId = $('#input-id').val();
    const lafadz = $('#input-lafadz').val().trim();
    if (!lafadz) {
        alert('Lafadz tidak boleh kosong');
        form.stopProgress();
        return;
    }

    // Logic
    // get key from local storage
    const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
    const stored = JSON.parse(localStorage.getItem(currentKey));

    // get active wordgroup
    const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');
    const wordGroup = stored.data.wordGroups.find(g => g.id == activeWordGroupId);

    const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
    if (groupIndex === -1) {
        alert('WordGroup tidak ditemukan');
        return;
    }

    const newWord = {
        id: wordId ?? Date.now(),
        text: $('#input-lafadz').val(),
        translation: $('#input-translation').val(),
        kalimah: $('#input-kalimah').val(),
        jenis: $('#input-variation').val(),
        hukum: $('#input-hukum').val(),
        mabni_detail: $('#input-mabni-detail').val(),
        category: $('#input-kategori').val(),
        kedudukan: $('#input-mahal').val(),
        irob: $('#input-irob').val(),
        alamat: $('#input-alamat').val(),
        condition: $('#input-condition').val(),
        matbu: $('#input-matbu').val(),
    };

    console.log('Word Id:', wordId);

    // get mode
    if (wordId) {
        // MODE EDIT
        const words = stored.data.wordGroups[groupIndex].words || [];
        const wordIndex = words.findIndex(w => w.id == wordId);

        if (wordIndex !== -1) {
            stored.data.wordGroups[groupIndex].words[wordIndex] = newWord;
        } else {
            console.warn('Word tidak ditemukan, menambahkan sebagai baru');
            word.push(newWord);
        }
    } else {
        // MODE ADD
        if (!stored.data.wordGroups[groupIndex].words) {
            stored.data.wordGroups[groupIndex].words = [];
        }
        stored.data.wordGroups[groupIndex].words.push(newWord);
    }

    // save to local storage
    localStorage.setItem(currentKey, JSON.stringify(stored));

    // re render word table
    renderWordsTable(wordGroup);

    // form.stopProgress();
    $('#form-add-word')[0].reset();
    $('#modal-add-word').modal('hide');
});

// $('#btn-add-word').fireModal({
//     title: 'Tambah Kalimat',
//     body: $('#form-add-word'),
//     footerClass: 'bg-whitesmoke',
//     autofocus: true,
//     onFormSubmit: function (modal, e, form) {
//         e.preventDefault();

//         const lafadz = $('#input-lafadz').val().trim();
//         if (!lafadz) {
//             alert('Lafadz tidak boleh kosong');
//             form.stopProgress();
//             return;
//         }

//         // logic
//         const newWord = {
//             id: Date.now(),
//             text: $('#input-lafadz').val(),
//             translation: $('#input-translation').val(),
//             kalimah: $('#input-kalimah').val(),
//             jenis: $('#input-variation').val(),
//             hukum: $('#input-hukum').val(),
//             mabni_detail: $('#input-mabni-detail').val(),
//             category: $('#input-kategori').val(),
//             kedudukan: $('#input-mahal').val(),
//             irob: $('#input-irob').val(),
//             alamat: $('#input-alamat').val(),
//             condition: $('#input-condition').val(),
//             matbu: $('#input-matbu').val(),
//         }

//         console.log('Word baru:', newWord);

//         // Logic
//         // get key from local storage
//         const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
//         const stored = JSON.parse(localStorage.getItem(currentKey));

//         // get active wordgroup
//         const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');
//         const wordGroup = stored.data.wordGroups.find(g => g.id == activeWordGroupId);

//         const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
//         if (groupIndex === -1) {
//             alert('WordGroup tidak ditemukan');
//             return;
//         }

//         // add to words list
//         if (!stored.data.wordGroups[groupIndex].words) {
//             stored.data.wordGroups[groupIndex].words = [];
//         }
//         stored.data.wordGroups[groupIndex].words.push(newWord);

//         // save to local storage
//         localStorage.setItem(currentKey, JSON.stringify(stored));

//         // re render word table
//         renderWordsTable(wordGroup);

//         form.stopProgress();
//         $(this)[0].reset();
//         modal.modal('hide');
//     },
//     shown: function (modal, form) {
//         console.log(form)
//     },
//     buttons: [
//         {
//             text: 'Tambahkan',
//             submit: true,
//             class: 'btn btn-primary btn-shadow',
//             handler: function (modal) {
//             }
//         }
//     ]
// });

// $(document).on('hidden.bs.modal', '.modal', function () {
//     if (document.activeElement) {
//         document.activeElement.blur();
//     }
// });

// Render Words Table
function renderWordsTable(wordGroup) {
    const tbody = $('#sortable-table tbody');
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);
        return;
    }

    wordGroup.words.forEach(word => {
        let badgeClass = 'badge-light';
        if (word.kalimah === 'فعل') badgeClass = 'badge-success';
        else if (word.kalimah === 'اسم') badgeClass = 'badge-info';
        else if (word.kalimah === 'حرف') badgeClass = 'badge-danger';

        const row = `
            <tr>
                <td class="text-center align-middle w-5">
                    <div class="sort-handler align-middle">
                        <i class="fa-solid fa-grip"></i>
                    </div>
                </td>
                <td class="text-center align-middle w-5">
                    <div class="arabic-text words" id="${word.id}">${word.text}</div>
                    <div class="table-links">
                        <a href="#" class="word-detail">Detail</a>
                        <div class="bullet"></div>
                        <a href="#" class="word-edit">Edit</a>
                        <div class="bullet"></div>
                        <a href="#" class="text-danger word-delete" id="btl-delete">Hapus</a>
                    </div>
                </td>
                <td class="text-center align-middle translation">${word.translation ?? ''}</td>
                <td class="text-center align-middle">
                    <div class="badge ${badgeClass}">${word.kalimah ?? ''}</div>
                </td>
                <td class="arabic-text">${word.kedudukan ?? ''}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Delete word row
$(document).on('click', '.table-links .word-delete', function (e) {
    e.preventDefault();

    // confirm deletion
    swal({
        title: 'Konfirmasi',
        text: 'Yakin ingin menghapus kalimat ini?',
        buttons: {
            cancel: {
                text: 'Batal',
                visible: true,
            },
            confirm: {
                text: 'Hapus',
                visible: true,
                className: 'btn-danger'
            },
        },
    }).then((willSave) => {
        if (!willSave) return;

        const wordId = $(this).closest('tr').find('.words').attr('id');

        // get data from local storage
        const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
        if (!currentKey) return;

        const stored = JSON.parse(localStorage.getItem(currentKey));

        // get active wordgroup
        const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');
        console.log('activeWordGroupId: ', activeWordGroupId);
        const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
        if (groupIndex === -1) return;

        // delete word base on Id
        stored.data.wordGroups[groupIndex].words = stored.data.wordGroups[groupIndex].words.filter(w => w.id != wordId);

        // save again
        localStorage.setItem(currentKey, JSON.stringify(stored));

        // render table
        const updatedGroup = stored.data.wordGroups[groupIndex];
        renderWordsTable(updatedGroup);
    });
});

// Edit Word Row
$(document).on('click', '.table-links .word-edit', function (e) {
    e.preventDefault();

    const tr = $(this).closest('tr');
    const wordId = tr.find('.words').attr('id');

    // get data from local storage
    const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
    const stored = JSON.parse(localStorage.getItem(currentKey));
    const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');
    const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
    const word = stored.data.wordGroups[groupIndex].words.find(w => w.id == wordId);

    // fill modal form
    $('#input-id').val(word.id);
    $('#input-lafadz').val(word.text);
    $('#input-translation').val(word.translation);

    // $('#form-add-word').data('edit-id', wordId);
    console.log('hello first');

    $('#modal-add-word').modal('show');
});

// TEST
