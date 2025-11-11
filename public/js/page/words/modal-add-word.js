"use strict";

// // add word clicked
// $('#btn-add-word').on('click', function(){
//     $('#modal-add-word').modal('show');
// });

// // submit
// $('#form-add-word').on('submit', function (e) {
//     e.preventDefault();


// })

$('#btn-add-word').fireModal({
    title: 'Tambah Kalimat',
    body: $('#form-add-word'),
    footerClass: 'bg-whitesmoke',
    autofocus: true,
    onFormSubmit: function (modal, e, form) {
        e.preventDefault();

        const lafadz = $('#input-lafadz').val().trim();
        if (!lafadz) {
            alert('Lafadz tidak boleh kosong');
            form.stopProgress();
            return;
        }

        // logic
        const newWord = {
            id: Date.now(),
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
        }

        console.log('Word baru:', newWord);

        // Logic
        // get key from local storage
        const currentKey = Object.keys(localStorage).find(k => k.startsWith('wordgroups_'));
        const stored = JSON.parse(localStorage.getItem(currentKey));

        // get active wordgroup
        const activeWordGroupId = $('.owl-item.active .word-group').attr('wg-id');

        const groupIndex = stored.data.wordGroups.findIndex(g => g.id == activeWordGroupId);
        if (groupIndex === -1) {
            alert('WordGroup tidak ditemukan');
            return;
        }

        // add to words list
        if (!stored.data.wordGroups[groupIndex].words) {
            stored.data.wordGroups[groupIndex].words = [];
        }
        stored.data.wordGroups[groupIndex].words.push(newWord);

        // save to local storage
        localStorage.setItem(currentKey, JSON.stringify(stored));

        // re render word table
        // renderWordsTable(activeWordGroupId);

        form.stopProgress();
        $(this)[0].reset();
        modal.modal('hide');
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [
        {
            text: 'Tambahkan',
            submit: true,
            class: 'btn btn-primary btn-shadow',
            handler: function(modal) {
            }
        }
    ]
})