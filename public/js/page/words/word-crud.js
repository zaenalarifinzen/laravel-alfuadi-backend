"use strict";

// open modal
$("#btn-add-word").on("click", function () {
    $("#form-add-word")[0].reset();
    $("#form-add-word-label").text("Tambah Kalimat");
    $("#btn-submit").text("Tambahkan");
    $("#additional-fields").hide();

    // get key from local storage
    const currentKey = Object.keys(localStorage).find((k) =>
        k.startsWith("wordgroups_")
    );
    const stored = JSON.parse(localStorage.getItem(currentKey));

    // get active wordgroup
    const activeWordGroupId = $(".owl-item.active .word-group").attr("wg-id");
    const wordGroup = stored.data.wordGroups.find(
        (g) => g.id == activeWordGroupId
    );

    $("#input-id").val("");
    $("#input-lafadz").val(wordGroup.text);
    $("#input-kategori").val("");
    $("#input-hukum").val("");
    $("#input-irob").val("");
    $("#input-tanda").val("");
    $("#input-kedudukan").val("");
    $("#input-simbol").val("");

    $("#modal-add-word").modal("show");
});

$("#form-add-word").on("submit", function (e) {
    e.preventDefault();

    // Validate Input
    const wordId = $("#input-id").val();
    const lafadz = $("#input-lafadz").val().trim();
    const kalimat = $("#input-kalimat").val();

    // validate required fields
    if (!lafadz) {
        alert("Lafadz tidak boleh kosong");
        return;
    }

    // if wordId is not empty, it's in edit mode, so kalimat is required
    if (wordId && (!kalimat || kalimat.startsWith("Pilih"))) {
        alert("Kalimat tidak boleh kosong");
        return;
    }

    // Logic
    // get key from local storage
    const currentKey = Object.keys(localStorage).find((k) =>
        k.startsWith("wordgroups_")
    );
    const stored = JSON.parse(localStorage.getItem(currentKey));

    // get active wordgroup
    const activeWordGroupId = $(".owl-item.active .word-group").attr("wg-id");
    const wordGroup = stored.data.wordGroups.find(
        (g) => g.id == activeWordGroupId
    );

    const groupIndex = stored.data.wordGroups.findIndex(
        (g) => g.id == activeWordGroupId
    );
    if (groupIndex === -1) {
        alert("WordGroup tidak ditemukan");
        return;
    }

    const newOrder = (wordGroup.words?.length || 0) + 1;
    let color;
    if (lafadz === "-") {
        color = "black";
    } else {
        switch (kalimat) {
            case "10":
                color = "blue";
                break;
            case "21":
                color = "green";
                break;
            case "22":
                color = "green";
                break;
            case "23":
                color = "green";
                break;
            case "30":
                color = "red";
                break;
            default:
                color = "black";
                break;
        }
    }

    const newWord = {
        id: wordId || Date.now(),
        text: $("#input-lafadz").val().trim(),
        order_number: wordId ? $("#input-order-number").val() : newOrder,
        translation: $("#input-translation").val().trim(),
        kalimat: $("#input-kalimat option:selected").text(),
        color: color,
        kategori: $("#input-kategori option:selected").text() || null,
        hukum: $("#input-hukum").val(),
        kedudukan: $("#input-kedudukan option:selected").text() || null,
        irob: $("#input-irob").val(),
        tanda: $("#input-tanda").val(),
        simbol: $("#input-simbol").val(),
    };

    // get mode
    if (wordId) {
        // MODE EDIT
        const words = stored.data.wordGroups[groupIndex].words || [];
        const wordIndex = words.findIndex((w) => w.id == wordId);

        if (wordIndex !== -1) {
            stored.data.wordGroups[groupIndex].words[wordIndex] = newWord;
        } else {
            console.warn("Word tidak ditemukan, menambahkan sebagai baru");
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

    // track modification
    // modified = true;
    markModified();

    // re render word table & details
    renderWordsTable(wordGroup);
    renderWordsDetails(wordGroup);

    // form.stopProgress();
    $("#form-add-word")[0].reset();
    $("#modal-add-word").modal("hide");
});

// Render Words Table
function renderWordsTable(wordGroup) {
    const tbody = $("#sortable-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0)
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const row = `
            <tr>
                <td class="text-center align-middle">
                    <div class="sort-handler align-middle">
                        <i class="fa-solid fa-grip"></i>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <div class="${simbolClass} dropdown d-inline arabic-text words" id="${
            word.id
        }" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">${
            word.text
        }</div>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item has-icon word-edit"><i class="far fa-edit"></i> Edit</a>
                        <a href="#" class="dropdown-item has-icon text-danger word-delete" id="btl-delete"><i class="far fa-trash-can"></i> Hapus</a>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <div class="text-center ${simbolClass} mb-2 arabic-text ar-symbol">${
            word.simbol ?? ""
        }</div>
                </td>
                <td class="align-middle">${
                    word.translation ?? ""
                }</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Render Words Table
function renderWordsDetails(wordGroup) {
    const tbody = $("#detail-kalimat-table tbody");
    tbody.empty();

    if (!wordGroup || !wordGroup.words || wordGroup.words.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
        `);
        return;
    }

    // sort word based on order_number
    wordGroup.words.sort(
        (a, b) => (a.order_number || 0) - (b.order_number || 0)
    );

    wordGroup.words.forEach((word) => {
        let simbolClass = "text-dark";
        if (word.color === "red") simbolClass = "text-huruf";
        else if (word.color === "green") simbolClass = "text-fiil";
        else if (word.color === "blue") simbolClass = "text-isim";

        const row = `
            <tr class="text-center kalimat-detail-row">
                <td>
                    <div class="text-right arabic-text words">
                        ${word.kalimat} - ${word.hukum} -
                        ${word.kategori} - ${word.kedudukan} -
                        ${word.irob} - ${word.tanda}
                    </div>
                </td>
                <td class="text-center align-middle word" id="${word.id}">
                       <div class="${simbolClass} arabic-text words">
                           ${word.text}
                       </div>
                          <div class="text-center ${simbolClass} mb-2 arabic-text ar-symbol-mini">
                            ${word.simbol ?? ""}
                        </div>
                       <div class="translation">
                           ${word.translation ?? ""}
                       </div>
                   </td>
             </tr>
             
        `;
        tbody.append(row);
    });
}

// Delete word row
$(document).on("click", ".dropdown-menu .word-delete", function (e) {
    e.preventDefault();

    // confirm deletion
    swal({
        title: "Konfirmasi",
        text: "Yakin ingin menghapus kalimat ini?",
        buttons: {
            cancel: {
                text: "Batal",
                visible: true,
            },
            confirm: {
                text: "Hapus",
                visible: true,
                className: "btn-danger",
            },
        },
    }).then((willSave) => {
        if (!willSave) return;

        const wordId = $(this).closest("tr").find(".words").attr("id");

        // get data from local storage
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith("wordgroups_")
        );
        if (!currentKey) return;

        const stored = JSON.parse(localStorage.getItem(currentKey));

        // get active wordgroup
        const activeWordGroupId = $(".owl-item.active .word-group").attr(
            "wg-id"
        );
        // console.log('activeWordGroupId: ', activeWordGroupId);
        const groupIndex = stored.data.wordGroups.findIndex(
            (g) => g.id == activeWordGroupId
        );
        if (groupIndex === -1) return;

        // delete word base on Id
        stored.data.wordGroups[groupIndex].words = stored.data.wordGroups[
            groupIndex
        ].words.filter((w) => w.id != wordId);

        // save again
        localStorage.setItem(currentKey, JSON.stringify(stored));

        // track modification
        // modified = true;
        markModified();

        // render table
        const updatedGroup = stored.data.wordGroups[groupIndex];
        renderWordsTable(updatedGroup);
        renderWordsDetails(updatedGroup);
    });
});

// Edit Word Row
$(document).on("click", ".dropdown-menu .word-edit", function (e) {
    e.preventDefault();

    const tr = $(this).closest("tr");
    const wordId = tr.find(".words").attr("id");

    // get data from local storage
    const currentKey = Object.keys(localStorage).find((k) =>
        k.startsWith("wordgroups_")
    );
    const stored = JSON.parse(localStorage.getItem(currentKey));
    const activeWordGroupId = $(".owl-item.active .word-group").attr("wg-id");
    const groupIndex = stored.data.wordGroups.findIndex(
        (g) => g.id == activeWordGroupId
    );
    const word = stored.data.wordGroups[groupIndex].words.find(
        (w) => w.id == wordId
    );

    // fill modal form
    $("#input-translation").required = true;
    $("#input-id").val(word.id);
    $("#input-order-number").val(word.order_number);
    $("#input-lafadz").val(word.text);
    $("#input-translation").val(word.translation);

    $("#input-kalimat").val(word.kalimat).change();
    $("#input-kategori").val(word.kategori).change();
    $("#input-hukum").val(word.hukum).change();
    $("#input-irob").val(word.irob).change();
    $("#input-tanda").val(word.tanda).change();
    $("#input-kedudukan").val(word.kedudukan).change();
    $("#input-simbol").val(word.simbol).change();

    $("#form-add-word-label").text("Update Kalimat");
    $("#btn-submit").text("Update");
    $("#additional-fields").show();
    $("#modal-add-word").modal("show");
});

// Save All Word
$("#btn-save-all").on("click", function (e) {
    e.preventDefault();

    const currentKey = Object.keys(localStorage).find((k) =>
        k.startsWith("wordgroups_")
    );
    if (!currentKey) {
        alert("Tidak ada data");
        return;
    }

    const stored = JSON.parse(localStorage.getItem(currentKey));
    if (
        !stored ||
        !stored.data ||
        !stored.data.wordGroups ||
        stored.data.wordGroups.length === 0
    ) {
        alert("Data kosong");
        return;
    }

    const emptyGroups = stored.data.wordGroups.filter(
        (g) => !g.words || g.words.length === 0
    );
    if (emptyGroups.length > 0) {
        let list = emptyGroups.map((g) => `${g.text}`).join(" - ");

        swal({
            icon: "warning",
            title: "Data belum lengkap",
            text: "Grup dengan kalimat masih kosong :\n\n" + list,
        });
        return;
    }

    // confirmation
    swal({
        title: "Konfirmasi",
        text: "Yakin ingin menyimpan ayat ini?",
        buttons: {
            cancel: {
                text: "Batal",
                visible: true,
            },
            confirm: {
                text: "Simpan",
                visible: true,
                className: "btn-success",
            },
        },
    }).then((willSave) => {
        if (!willSave) return;

        // Logic
        $.ajax({
            url: WORDS_SYNC_URL,
            type: "POST",
            data: {
                _token: CSRF_TOKEN,
                verse_id: stored.data.verse.id,
                groups: stored.data.wordGroups,
            },
            beforeSend: function () {
                $("#btn-save-all").prop("disabled", true).text("Menyimpan...");
            },
            success: function () {
                iziToast.success({
                    message: "Data berhasil disimpan",
                    position: "topRight",
                });
                localStorage.removeItem(currentKey);

                // load next verse
                const nextVerse = stored.data.verse.id + 1;
                fetchWordGroups(null, null, nextVerse);
            },
            error: function (xhr) {
                console.error("Save error: ", xhr.responseText);
                iziToast.error({
                    message: "Terjadi kesalahan saat menyimpan data",
                    position: "topRight",
                });
            },
            complete: function () {
                $("#btn-save-all").prop("disabled", false).text("Simpan");
            },
        });
    });
});
