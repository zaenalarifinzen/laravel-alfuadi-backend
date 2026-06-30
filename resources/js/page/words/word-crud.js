"use strict";

export function initWordCrud({
    config,
    getPrefix,
    storage,
    markModified,
    renderWordsTable,
    renderWordsDetails,
    fetchWordGroups,
    getNahwuController,
    getCurrentCompareResult,
    compareAnswers,
    highlightErrors,
}) {
    // =========================
    // RESET FORM HELPER
    // =========================
    function resetWordForm() {
        // 1. reset native form
        $("#form-add-word")[0].reset();
        $('#input-id').val("");
        $("#input-order-number").val("");

        // 2. clear error validation
        clearInputError("#input-lafadz");
        clearInputError("#input-translation");

        // 3. reset all custom dropdown
        const ctrl = getNahwuController();
        if (ctrl) {
            ctrl.resetDropdown(ctrl.instances.kalimat);
            ctrl.resetAllDropdown();

            // enable all dropdown
            ctrl.enableAllRelationFields();

            // set default category
            ctrl.updateKategoriOptions();
        }

        // 4. release addition attribute
        document
            .getElementById("form-add-word")
            .querySelector("[required]")
            .forEach((el) => el.removeAttribute("required"));

        // 5. hide additional fields & set label to defautl
        $("#additional-fields").hide();
        $("#form-add-word-label").text("Tambah Kalimat");
        $("#btn-submit").text("Tambahkan");
    }

    $("#modal-add-word").on("hidden.bs.modal", function () {
        resetWordForm();
    });

    // open modal
    $("#btn-add-word").on("click", function () {
        $("#form-add-word")[0].reset();
        $("#form-add-word-label").text("Tambah Kalimat");
        $("#btn-submit").text("Tambahkan");

        clearInputError("#input-lafadz");
        clearInputError("#input-translation");

        // remove required attribute
        document
            .getElementById("form-add-word")
            .querySelectorAll("[required]")
            .forEach((el) => {
                el.removeAttribute("required");
            });
        $("#additional-fields").hide();

        // get key from local storage
        const prefix = getPrefix();
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith(prefix),
        );
        const stored = JSON.parse(localStorage.getItem(currentKey));

        // get active wordgroup
        const activeWordGroupId = $(".owl-item.active .word-group").attr(
            "wg-id",
        );
        const wordGroup = stored.wordGroups.find(
            (g) => g.id == activeWordGroupId,
        );

        $("#input-id").val("");
        $("#input-lafadz").val(wordGroup.text);
        $("#input-kategori").val("");
        $("#input-hukum").val("");
        $("#input-irob").val("");
        $("#input-tanda").val("");
        $("#input-kedudukan").val("");
        $("#input-simbol").val("");

        $("#input-lafadz").attr("required", true);
        $("#modal-add-word").modal("show");
    });

    $("#form-add-word").on("submit", function (e) {
        e.preventDefault();
        let valid = true;

        const editMode = $("#additional-fields").is(":visible");

        const controller = getNahwuController();
        if (editMode && controller) {
            if (!validateInput("#input-lafadz")) valid = false;
            if (!validateInput("#input-translation")) valid = false;

            Object.values(controller.instances).forEach((instance) => {
                if (!instance.validate()) valid = false;
            });
            if (!valid) return;
        }

        const wordId = $("#input-id").val();
        const lafadz = $("#input-lafadz").val().trim();
        const kalimat = $("#input-kalimat").val();
        const numericWordId = wordId ? Number(wordId) : null;

        // Logic
        // get key from local storage
        const prefix = getPrefix();
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith(prefix),
        );

        const stored = JSON.parse(localStorage.getItem(currentKey));

        // get active wordgroup
        const activeWordGroupId = $(".owl-item.active .word-group").attr(
            "wg-id",
        );
        const wordGroup = stored.wordGroups.find(
            (g) => g.id == activeWordGroupId,
        );

        const groupIndex = stored.wordGroups.findIndex(
            (g) => g.id == activeWordGroupId,
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

        function getSelectVal(id) {
            const val = $(id).val();
            return val ? val : null;
        }

        function getSelectText(id) {
            const val = $(id).val();
            if (!val) return null;
            const text = $(`${id} option:selected`).text().trim();
            return text && !text.startsWith("Pilih") ? text : null;
        }

        const newWord = {
            id: numericWordId ?? Date.now(),
            text: $("#input-lafadz").val().trim(),
            order_number: wordId
                ? Number($("#input-order-number").val())
                : newOrder,
            translation: $("#input-translation").val().trim(),

            // save id
            kalimat_id: $("#input-kalimat").val(),
            kategori_id: $("#input-kategori").val(),
            kedudukan_id: $("#input-kedudukan").val(),

            kalimat: getSelectText("#input-kalimat"),
            color: color,
            kategori: getSelectText("#input-kategori"),
            hukum: getSelectVal("#input-hukum"),
            kedudukan: getSelectText("#input-kedudukan"),
            irob: getSelectVal("#input-irob"),
            tanda: getSelectVal("#input-tanda"),
            simbol: getSelectVal("#input-simbol"),
        };

        // get mode
        if (wordId) {
            // MODE EDIT
            const words = stored.wordGroups[groupIndex].words || [];
            const wordIndex = words.findIndex((w) => w.id == wordId);

            if (wordIndex !== -1) {
                stored.wordGroups[groupIndex].words[wordIndex] = newWord;
            } else {
                console.warn("Word tidak ditemukan, menambahkan sebagai baru");
                words.push(newWord);
            }
        } else {
            // MODE ADD
            if (!stored.wordGroups[groupIndex].words) {
                stored.wordGroups[groupIndex].words = [];
            }
            stored.wordGroups[groupIndex].words.push(newWord);
        }

        // save to local storage
        localStorage.setItem(currentKey, JSON.stringify(stored));

        // track modification
        // modified = true;
        markModified(prefix);

        // re render word table & details
        renderWordsTable(wordGroup);
        renderWordsDetails(wordGroup);

        if (getCurrentCompareResult().length !== 0) {
            const compareResult = compareAnswers(stored.verse.id);
            highlightErrors(compareResult);
        }

        // show save-all button
        $("#btn-save-all").show();

        // form.stopProgress();
        $("#modal-add-word").modal("hide");
    });

    // Delete word row
    $(document).on("click", ".action-buttons .word-delete", function (e) {
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
            const prefix = getPrefix();
            const currentKey = Object.keys(localStorage).find((k) =>
                k.startsWith(prefix),
            );
            if (!currentKey) return;

            const stored = JSON.parse(localStorage.getItem(currentKey));

            // get active wordgroup
            const activeWordGroupId = $(".owl-item.active .word-group").attr(
                "wg-id",
            );
            // console.log('activeWordGroupId: ', activeWordGroupId);
            const groupIndex = stored.wordGroups.findIndex(
                (g) => g.id == activeWordGroupId,
            );
            if (groupIndex === -1) return;

            // delete word base on Id
            stored.wordGroups[groupIndex].words = stored.wordGroups[
                groupIndex
            ].words.filter((w) => w.id != wordId);

            // save again
            localStorage.setItem(currentKey, JSON.stringify(stored));

            // track modification
            // modified = true;
            markModified(prefix);

            // render table
            const updatedGroup = stored.wordGroups[groupIndex];
            renderWordsTable(updatedGroup);
            renderWordsDetails(updatedGroup);
        });
    });

    // Edit Word Row
    $(document).on("click", ".action-buttons .word-edit", function (e) {
        e.preventDefault();

        clearInputError("#input-lafadz");
        clearInputError("#input-translation");

        const tr = $(this).closest("tr");
        const wordId = tr.find(".words").attr("id");

        // get data from local storage
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith(getPrefix()),
        );
        const stored = JSON.parse(localStorage.getItem(currentKey));
        const activeWordGroupId = $(".owl-item.active .word-group").attr(
            "wg-id",
        );
        const groupIndex = stored.wordGroups.findIndex(
            (g) => g.id == activeWordGroupId,
        );
        const word = stored.wordGroups[groupIndex].words.find(
            (w) => w.id == wordId,
        );

        // fill input biasa
        $("#input-translation").required = true;
        $("#input-id").val(word.id);
        $("#input-order-number").val(word.order_number);
        $("#input-lafadz").val(word.text);
        $("#input-translation").val(word.translation);

        const ctrl = getNahwuController();
        if (ctrl) {
            const { kalimat_id, kategori_id, kedudukan_id } =
                ctrl.resolveIds(word);

            // 1. Set kalimat
            ctrl.instances.kalimat?.setValueById(kalimat_id);
            ctrl.instances.kalimat?.select.dispatchEvent(
                new CustomEvent("change", { detail: { isRestoring: true } }),
            );

            // 2. Set hukum (kalimat already set, so kategori dropdown will update based on kalimat + hukum)
            setTimeout(() => {
                const isHukumEnabled = ctrl.instances.hukum?.isEnabled();
                if (isHukumEnabled) {
                    ctrl.instances.hukum?.setValueById(word.hukum);
                    ctrl.instances.hukum?.select.dispatchEvent(
                        new CustomEvent("change", {
                            detail: { isRestoring: true },
                        }),
                    );
                }

                // Wait for kategori dropdown to update based on hukum change
                setTimeout(() => {
                    const isKategoriEnabled = ctrl.instances.kategori?.isEnabled();
                    if (isKategoriEnabled) {
                        ctrl.instances.kategori?.setValueById(kategori_id);
                        ctrl.instances.kategori?.select.dispatchEvent(
                            new CustomEvent("change", {
                                detail: { isRestoring: true },
                            }),
                        );
                    }

                    // Then set kedudukan and other fields
                    setTimeout(() => {
                        const isKedudukanEnabled = ctrl.instances.kedudukan?.isEnabled();
                        if (isKedudukanEnabled) {
                            ctrl.instances.kedudukan?.setValueById(kedudukan_id);
                            ctrl.instances.kedudukan?.select.dispatchEvent(
                                new CustomEvent("change", {
                                    detail: { isRestoring: true },
                                }),
                            );
                        }

                        ctrl.instances.irob?.setValueById(word.irob);
                        ctrl.instances.tanda?.setValueById(word.tanda);
                        ctrl.instances.simbol?.setValueById(word.simbol);
                    }, 100);
                }, 100);
            }, 50);
        }

        // set lafadz & transtalion required
        $("#input-lafadz").attr("required", true);

        $("#form-add-word-label").text("Update Kalimat");
        $("#btn-submit").text("Update");
        $("#additional-fields").show();
        $("#modal-add-word").modal("show");
    });

    // Save All Word
    $("#btn-save-all").on("click", function (e) {
        e.preventDefault();

        const prefix = getPrefix();
        const currentKey = Object.keys(localStorage).find((k) =>
            k.startsWith(prefix),
        );
        if (!currentKey) {
            alert("Tidak ada data");
            return;
        }

        const stored = JSON.parse(localStorage.getItem(currentKey));
        if (!stored || !stored.wordGroups || stored.wordGroups.length === 0) {
            alert("Data kosong");
            return;
        }

        const emptyGroups = stored.wordGroups.filter(
            (g) => !g.words || g.words.length === 0,
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
                url: config.wordsSyncUrl,
                type: "POST",
                data: {
                    _token: config.csrfToken,
                    verse_id: stored.verse.id,
                    groups: stored.wordGroups,
                },
                beforeSend: function () {
                    $("#btn-save-all")
                        .prop("disabled", true)
                        .text("Menyimpan...");
                },
                success: function () {
                    iziToast.success({
                        message: "Data berhasil disimpan",
                        position: "topRight",
                    });
                    localStorage.removeItem(currentKey);

                    // load next verse
                    const nextVerse = stored.verse.id + 1;
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

    // ========================
    // HELPER
    // ========================

    function validateInput(selector, message = "Wajib diisi") {
        const input = $(selector);
        const wrapper = input.closest(".form-group");
        let errorElement = wrapper.find(".error-message");

        if (!errorElement.length) {
            input.after(`<small class="error-message"></small>`);
            errorElement = wrapper.find(".error-message");
        }

        if (!input.val().trim()) {
            input.addClass("invalid");
            errorElement.text(message);
            return false;
        }

        input.removeClass("invalid");
        errorElement.text("");
        return true;
    }

    function clearInputError(selector) {
        const input = $(selector);
        const wrapper = input.closest(".form-group");
        input.removeClass("invalid");
        wrapper.find(".error-message").text("");
    }
}
