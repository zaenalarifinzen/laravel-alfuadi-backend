"use strict";

export function initAnalysisAnswerHandler({
    getPrefix,
    markModified,
    renderWordsTable,
    renderWordsDetails,
    getNahwuController,
    getCurrentCompareResult,
    setCurrentCompareResult,
    getCurrentExerciseState,
    getNavigationState,
    getCachedExerciseData,
    saveCachedExerciseData,
    fetchExercise,
    compareAnswers,
    highlightErrors,
    changeSubmitButton,
    resetCard,
}) {
    function resetWordForm() {
        // 1. reset native form
        $("#form-add-word")[0].reset();
        $("#input-id").val("");
        $("#input-order-number").val("");

        // 2. clear error validation
        clearInputError("#input-lafadz");
        clearInputError("#input-translation");

        // 3. reset all custom dropdown
        const ctrl = getNahwuController();
        if (ctrl) {
            ctrl.resetDropdown(ctrl.instances.kalimat);
            ctrl.resetAllDropdowns();

            // enable all dropdown
            ctrl.enableAllRelationFields();

            // set default category
            ctrl.updateKategoriOptions();
        }

        // 4. release addition attribute
        document
            .getElementById("form-add-word")
            .querySelectorAll("[required]")
            .forEach((el) => el.removeAttribute("required"));

        // 5. hide additional fields & set label to defautl
        $("#additional-fields").hide();
        $("#form-add-word-label").text("Tambah Kalimat");
        $("#btn-submit").text("Tambahkan");
    }

    $("#modal-add-word").on("hidden.bs.modal", function () {
        resetWordForm();
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
        // get data from local storage
        const stored = getCachedExerciseData();
        if (!stored) {
            alert("Data soal tidak ditemukan");
            return;
        }

        // get active user answer wordgroup
        const activeWordGroupId = $(".swiper-slide-active .word-group").attr(
            "wg-id",
        );
        const userAnswerWordGroup = stored.userAnswer.find(
            (g) => g.id == activeWordGroupId,
        );

        const userAnswerWordGroupIndex = stored.userAnswer.findIndex(
            (g) => g.id == activeWordGroupId,
        );
        if (userAnswerWordGroupIndex === -1) {
            alert("WordGroup tidak ditemukan");
            return;
        }

        const newOrder = (userAnswerWordGroup.words?.length || 0) + 1;
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
            const words =
                stored.userAnswer[userAnswerWordGroupIndex].words || [];
            const wordIndex = words.findIndex((w) => w.id == wordId);

            if (wordIndex !== -1) {
                newWord.hints = { ...(words[wordIndex].hints || {}) };
                stored.userAnswer[userAnswerWordGroupIndex].words[wordIndex] =
                    newWord;
            } else {
                console.warn("Word tidak ditemukan, menambahkan sebagai baru");
                words.push(newWord);
            }
        } else {
            // MODE ADD
            if (!stored.userAnswer[userAnswerWordGroupIndex].words) {
                stored.userAnswer[userAnswerWordGroupIndex].words = [];
            }
            stored.userAnswer[userAnswerWordGroupIndex].words.push(newWord);
        }

        // save to local storage
        saveCachedExerciseData(stored);

        // track modification
        // modified = true;
        markModified(getPrefix());
        changeSubmitButton("btn-submit-answer", "Submit", "primary");

        // re render word table
        renderWordsTable(userAnswerWordGroup);
        renderWordsDetails(stored.wordGroups[userAnswerWordGroupIndex]);

        if (getCurrentCompareResult().length !== 0) {
            const compareResult = compareAnswers();
            setCurrentCompareResult(compareResult);
            highlightErrors(compareResult);
        }

        // show save-all button
        $("#btn-save-all").show();

        // form.stopProgress();
        $("#modal-add-word").modal("hide");
    });

    // Edit Word Row
    $(document).on("click", ".action-buttons .word-edit", function (e) {
        e.preventDefault();

        clearInputError("#input-lafadz");
        clearInputError("#input-translation");

        const tr = $(this).closest("tr");
        const wordId = tr.find(".words").attr("id");

        // get data from local storage
        const stored = getCachedExerciseData();
        if (!stored) return;

        const activeWordGroupId = $(".swiper-slide-active .word-group").attr(
            "wg-id",
        );
        const groupIndex = stored.userAnswer.findIndex(
            (g) => g.id == activeWordGroupId,
        );
        const word = stored.userAnswer[groupIndex].words.find(
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

            // 1. Set kalimat dulu
            ctrl.instances.kalimat?.setValueById(kalimat_id);
            ctrl.instances.kalimat?.select.dispatchEvent(
                new CustomEvent("change", { detail: { isRestoring: true } }),
            );

            // 2. Set child field
            setTimeout(() => {
                ctrl.instances.hukum?.setValueById(word.hukum);
                ctrl.instances.hukum?.select.dispatchEvent(
                    new CustomEvent("change", {
                        detail: { isRestoring: true },
                    }),
                );

                setTimeout(() => {
                    ctrl.instances.kategori?.setValueById(kategori_id);
                    ctrl.instances.kategori?.select.dispatchEvent(
                        new CustomEvent("change", {
                            detail: { isRestoring: true },
                        }),
                    );

                    setTimeout(() => {
                        ctrl.instances.kedudukan?.setValueById(kedudukan_id);
                        ctrl.instances.kedudukan?.select.dispatchEvent(
                            new CustomEvent("change", {
                                detail: { isRestoring: true },
                            }),
                        );

                        ctrl.instances.irob?.setValueById(word.irob);
                        ctrl.instances.tanda?.setValueById(word.tanda);
                        ctrl.instances.simbol?.setValueById(word.simbol);
                    }, 50);
                }, 50);
            }, 50);
        }

        // set lafadz & transtalion required
        $("#input-lafadz").attr("required", true);

        $("#form-add-word-label").text("Update Kalimat");
        $("#btn-submit").text("Update");
        $("#additional-fields").show();
        $("#modal-add-word").modal({ backdrop : "static" }).modal("show");
    });

    // HINT BUTTON CLICK (LAMPU)
    $(document).on("click", ".btn-cell-hint", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const wordId = btn.attr("data-word-id");
        const field = btn.attr("data-field");
        const expected = btn.attr("data-expected");

        const fieldLabels = {
            kalimat: "Kalimat",
            hukum: "Hukum",
            kategori: "Kategori",
            kedudukan: "Kedudukan",
            irob: "I'rob",
            tanda: "Tanda I'rob",
        };
        const fieldName = fieldLabels[field] || field;

        swal({
            title: "Buka Bantuan?",
            text: `Jawaban pada ${fieldName} akan diperbaiki dengan yang benar, namun ini akan mengurangi skor anda.`,
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    visible: true,
                    className: "btn btn-secondary",
                },
                confirm: {
                    text: "Perbaiki",
                    visible: true,
                    className: "btn btn-warning",
                },
            },
        }).then((willOpen) => {
            if (!willOpen) return;

            const stored = getCachedExerciseData();
            if (!stored) return;

            const activeWordGroupId = $(
                ".swiper-slide-active .word-group",
            ).attr("wg-id");
            const groupIndex = stored.userAnswer.findIndex(
                (g) => g.id == activeWordGroupId,
            );
            if (groupIndex === -1) return;

            const word = stored.userAnswer[groupIndex].words.find(
                (w) => String(w.id) === String(wordId),
            );
            if (!word) return;

            word[field] = expected;
            if (!word.hints) {
                word.hints = {};
            }
            word.hints[field] = true;

            // If field is kalimat, sync color as well if available
            if (field === "kalimat") {
                const keyGroup = stored.wordGroups.find(
                    (g) => g.id == activeWordGroupId,
                );
                const keyWord = keyGroup?.words?.find(
                    (w) => String(w.id) === String(wordId),
                );
                if (keyWord && keyWord.color) {
                    word.color = keyWord.color;
                }
            }

            saveCachedExerciseData(stored);
            markModified(getPrefix());

            renderWordsTable(stored.userAnswer[groupIndex]);
            const compareResult = compareAnswers();
            setCurrentCompareResult(compareResult);
            highlightErrors(compareResult);
        });
    });

    // SUBMIT USER ANSWER
    $(document).on("click", "button[name='btn-submit']", function (e) {
        e.preventDefault();

        const exerciseState = getCurrentExerciseState();
        const exerciseNumber = exerciseState.orderNumber ?? null;
        const exerciseLevelSlug = exerciseState.levelSlug ?? null;

        if (!exerciseNumber) {
            iziToast.warning({
                message: "Exercise tidak ditemukan",
                position: "topRight",
            });
            return;
        }

        // passed check
        const btnId = this.id;
        if (btnId === "btn-next-verse") {
            const { nextId } = getNavigationState();
            if (nextId == null) return;
            fetchExercise(exerciseLevelSlug, nextId);
            return;
        }

        const compareResult = compareAnswers();
        if (compareResult.length === 0) {
            iziToast.warning({
                message: "Tidak ada data untuk dibandingkan",
                position: "topRight",
            });
            return;
        }

        setCurrentCompareResult(compareResult);
        highlightErrors(compareResult);

        // Granular component calculation (6 fields per word)
        let totalComponents = 0;
        let correctComponents = 0;
        let hintedComponents = 0;
        let wrongComponents = 0;

        compareResult.forEach((item) => {
            item.fields.forEach((f) => {
                totalComponents++;
                if (f.correct) {
                    if (f.isHinted) {
                        hintedComponents++;
                    } else {
                        correctComponents++;
                    }
                } else {
                    wrongComponents++;
                }
            });
        });

        // If there are still incorrect fields that haven't been resolved
        if (wrongComponents > 0) {
            iziToast.warning({
                title: "Periksa Kembali",
                message: `Masih ada ${wrongComponents} isian yang belum tepat. Silakan periksa kolom bertanda merah atau gunakan bantuan 💡.`,
                position: "bottomRight",
                timeout: 5000,
            });
            return;
        }

        const score = totalComponents > 0
            ? Math.round((correctComponents / totalComponents) * 100)
            : 0;

        const passingGrade = 60;

        if (score >= passingGrade) {
            let titleBadge = "Selamat!";
            if (score >= 85) titleBadge = "Mumtaz! ⭐⭐⭐";
            else if (score >= 70) titleBadge = "Jayyid Jiddan! ⭐⭐";
            else titleBadge = "Maqbul! ⭐";

            const payload = {
                exercise_number: exerciseNumber,
                level: exerciseLevelSlug,
                pass: true,
                score: score,
                attempt_count: 1,
                time_spent: null,
                metadata: JSON.stringify({
                    total_words: compareResult.length,
                    total_components: totalComponents,
                    correct_independent: correctComponents,
                    hints_used: hintedComponents,
                    final_score: score,
                }),
            };

            $.ajax({
                url: "/user-answers",
                type: "POST",
                xhrFields: {
                    withCredentials: true,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                    "Content-Type": "application/json",
                },
                data: JSON.stringify(payload),
                beforeSend: function () {
                    $("#btn-submit-answer").text("Menyimpan...");
                },
                success: function (response) {
                    if (response.success) {
                        resetCard();
                        changeSubmitButton(
                            "btn-next-verse",
                            "Selanjutnya",
                            "primary",
                        );

                        swal({
                            icon: "success",
                            title: titleBadge,
                            text: `Nilai Anda: ${score}/100\n(Benar mandiri: ${correctComponents}/${totalComponents}, Bantuan: ${hintedComponents})`,
                            buttons: {
                                cancel: {
                                    text: "Tutup",
                                    visible: true,
                                },
                                confirm: {
                                    text: "Selanjutnya",
                                    visible: true,
                                },
                            },
                        }).then((willSave) => {
                            if (!willSave) return;

                            const { nextId } = getNavigationState();
                            if (nextId == null) return;
                            fetchExercise(exerciseLevelSlug, nextId);
                        });
                    } else {
                        iziToast.error({
                            message:
                                response.message || "Gagal menyimpan jawaban",
                            position: "topRight",
                        });
                    }
                },
                error: function (xhr) {
                    console.error("Error response status:", xhr.status);
                    console.error("Error response:", xhr.responseText);

                    let errorMessage =
                        "Terjadi kesalahan saat menyimpan jawaban";
                    if (xhr.status === 401) {
                        errorMessage =
                            "Anda belum login. Silakan login terlebih dahulu.";
                    } else if (xhr.status === 422) {
                        try {
                            const errors = JSON.parse(xhr.responseText);
                            console.error("Validation errors:", errors);
                            if (errors.errors) {
                                errorMessage =
                                    "Validation error: " +
                                    Object.values(errors.errors)
                                        .flat()
                                        .join(", ");
                            } else if (errors.message) {
                                errorMessage = errors.message;
                            }
                        } catch (e) {
                            errorMessage = "Validation error occurred";
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    iziToast.error({
                        message: errorMessage,
                        position: "bottomRight",
                    });
                },
                complete: function () {
                    $("#btn-submit-answer").text("Submit");
                },
            });
        } else {
            // Score below passing grade due to too many hints
            swal({
                icon: "warning",
                title: "Belum Mencapai KKM",
                text: `Nilai Anda ${score}/100 (minimal KKM adalah ${passingGrade}). Anda menggunakan ${hintedComponents} bantuan sehingga belum tuntas.\n\nApakah Anda ingin mengulang latihan ayat ini secara mandiri?`,
                buttons: {
                    cancel: {
                        text: "Tutup",
                        visible: true,
                        className: "btn btn-secondary",
                    },
                    confirm: {
                        text: "Ulangi Latihan",
                        visible: true,
                        className: "btn btn-warning",
                    },
                },
            }).then((willRetry) => {
                if (!willRetry) return;

                const stored = getCachedExerciseData();
                if (!stored) return;

                stored.userAnswer.forEach((group) => {
                    if (Array.isArray(group.words)) {
                        group.words.forEach((w) => {
                            w.kalimat = null;
                            w.hukum = null;
                            w.kategori = null;
                            w.kedudukan = null;
                            w.irob = null;
                            w.tanda = null;
                            w.color = null;
                            delete w.hints;
                        });
                    }
                });

                saveCachedExerciseData(stored);
                markModified(getPrefix());

                const activeWordGroupId = $(
                    ".swiper-slide-active .word-group",
                ).attr("wg-id");
                const activeGroup = stored.userAnswer.find(
                    (g) => g.id == activeWordGroupId,
                );
                renderWordsTable(activeGroup);
                setCurrentCompareResult([]);
                document
                    .querySelectorAll("#sortable-table tbody tr")
                    .forEach((tr) => {
                        tr.classList.remove("is-wrong", "is-correct");
                        tr.querySelectorAll("td").forEach((td) => {
                            td.classList.remove("is-wrong", "is-hinted");
                            td.querySelectorAll(".btn-cell-hint").forEach(
                                (btn) => btn.remove(),
                            );
                        });
                    });

                iziToast.info({
                    message:
                        "Jawaban telah direset. Silakan kerjakan kembali secara mandiri!",
                    position: "bottomRight",
                });
            });
        }
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