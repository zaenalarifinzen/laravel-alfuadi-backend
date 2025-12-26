document.addEventListener("DOMContentLoaded", async () => {
    // --- Load JSON Data ---
    const response = await fetch("/json/data-nahwu.json");
    const data = await response.json();

    // --- Get Form Element ---
    const translation = document.getElementById("input-translation");
    const kalimatSelect = document.getElementById("input-kalimat");
    const kategoriSelect = document.getElementById("input-kategori");
    const hukumSelect = document.getElementById("input-hukum");
    const irobSelect = document.getElementById("input-irob");
    const tandaSelect = document.getElementById("input-tanda");
    const kedudukanSelect = document.getElementById("input-kedudukan");
    const simbolSelect = document.getElementById("input-simbol");

    const disableFields = (...fields) =>
        fields.forEach((f) => {
            f.value = "";
            f.disabled = true;
            f.removeAttribute("required");
        });
    const enableFields = (...fields) =>
        fields.forEach((f) => {
            f.disabled = false;
            f.setAttribute("required", "required");
        });

    // --- isi dropdown Kategori ---
    function populateCategory() {
        const selectedKalimat = kalimatSelect.value.trim();

        const filteredKategori = data.kategori.filter(
            (k) => k.id_kalimat === selectedKalimat
        );
        kategoriSelect.innerHTML =
            "<option selected disabled>Pilih Kategori</option>";
        filteredKategori.forEach((k) => {
            kategoriSelect.innerHTML += `<option value="${k.id}">${k.kategori_ar}</option>`;
        });
    }

    // --- isi dropdown Kedudukan ---
    // change parameter to 1 or more kalimat ids
    function populateKedudukan(kalimat) {
        const kalimatIds = Array.isArray(kalimat) ? kalimat : [kalimat];

        let filteredKedudukan = [];
        kalimatIds.forEach((id) => {
            const kedudukanForKalimat = data.kedudukan.filter(
                (k) => k.id_kalimat === id
            );
            filteredKedudukan = filteredKedudukan.concat(kedudukanForKalimat);
        });

        kedudukanSelect.innerHTML =
            "<option selected disabled>Pilih Kedudukan</option>";
        filteredKedudukan.forEach((k) => {
            kedudukanSelect.innerHTML += `<option value="${k.id}">${k.kedudukan_ar}</option>`;
        });
    }

    // --- isi Hukum ---
    function fillHukum() {
        const selectedKalimat = kalimatSelect.value.trim();
        const kategori = data.kategori.find(
            (k) => k.id == kategoriSelect.value
        );
        if (!kategori) return;

        if (selectedKalimat === "41" || selectedKalimat === "42") {
            hukumSelect.innerHTML = `<option selected></option>`;
        } else {
            hukumSelect.innerHTML = `<option selected>${kategori.hukum}</option>`;
        }

        // add too other unique hukum in dropdown based on kalimat without duplicating
        // const filteredKategori = data.kategori.filter(
        //     (k) => k.id_kalimat === selectedKalimat
        // );

        // const uniqueHukum = new Set();
        // filteredKategori.forEach((k) => {
        //     if (k.hukum !== kategori.hukum) {
        //         uniqueHukum.add(k.hukum);
        //     }
        // });
        // uniqueHukum.forEach((h) => {
        //     hukumSelect.innerHTML += `<option>${h}</option>`;
        // });
    }

    // --- isi i'rob ---
    function fillIrob() {
        const selectedKedudukan = kedudukanSelect.value;
        const kd = data.kedudukan.find((k) => k.id == selectedKedudukan);
        if (!kd) return;

        irobSelect.innerHTML = `<option selected>${kd.irob}</option>`;
        // add too other unique irob in dropdown based on kalimat without duplicating
        // const selectedKalimat = kalimatSelect.value.trim();
        // const filteredKedudukan = data.kedudukan.filter(
        //     (k) => k.id_kalimat === selectedKalimat
        // );
        // const uniqueIrob = new Set();
        // filteredKedudukan.forEach((k) => {
        //     if (k.irob !== kd.irob) {
        //         uniqueIrob.add(k.irob);
        //     }
        // });
        // uniqueIrob.forEach((i) => {
        //     irobSelect.innerHTML += `<option>${i}</option>`;
        // });
    }

    // --- isi Tanda I‘rob ---
    function fillTanda() {
        const currentKalimat = kalimatSelect.value;
        // jika kalimat adalah isim muawal atau jumlah, maka fii mahal
        if (currentKalimat === "11" || currentKalimat === "41") {
            // ambil tanda irob yang mabni
            const kategoriMabni = data.kategori.find((k) => k.id === "C1");

            let tanda = "";
            const irob = irobSelect.value.trim();
            switch (irob) {
                case "مَرْفُوْعٌ":
                    tanda = kategoriMabni.rofa;
                    break;
                case "مَنْصُوْبٌ":
                    tanda = kategoriMabni.nashob;
                    break;
                case "مَجْرُوْرٌ":
                    tanda = kategoriMabni.jar;
                    break;
                case "مَجْزُوْمٌ":
                    tanda = kategoriMabni.jazm;
                    break;
            }

            tandaSelect.innerHTML = `<option selected>${tanda}</option>`;
        } else {
            const kategori = data.kategori.find(
                (k) => k.id == kategoriSelect.value
            );
            if (!kategori) return;

            let tanda = "";
            const irob = irobSelect.value.trim();
            switch (irob) {
                case "مَرْفُوْعٌ":
                    tanda = kategori.rofa;
                    break;
                case "مَنْصُوْبٌ":
                    tanda = kategori.nashob;
                    break;
                case "مَجْرُوْرٌ":
                    tanda = kategori.jar;
                    break;
                case "مَجْزُوْمٌ":
                    tanda = kategori.jazm;
                    break;
            }

            tandaSelect.innerHTML = `<option selected>${tanda}</option>`;
        }
    }

    // --- isi simbol ---
    function fillSimbol() {
        const currentKalimat = kalimatSelect.value;
        if (
            currentKalimat === "21" ||
            currentKalimat === "23" ||
            currentKalimat === "30"
        ) {
            const kategori = data.kategori.find(
                (k) => k.id == kategoriSelect.value
            );

            simbolSelect.innerHTML = `<option selected>${kategori.simbol}</option>`;
        } else {
            const selectedKedudukan = kedudukanSelect.value;
            const kd = data.kedudukan.find((k) => k.id == selectedKedudukan);
            if (!kd) return;

            simbolSelect.innerHTML = `<option selected>${kd.simbol}</option>`;
        }
    }

    // --- Fields Controller ---
    function fieldsController() {
        const currentKalimat = kalimatSelect.value;

        switch (currentKalimat) {
            case "21":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "23":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "30":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "41":
                enableFields(
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                disableFields(kategoriSelect, hukumSelect);
                break;
            case "42":
                enableFields(
                    kategoriSelect,
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                disableFields(hukumSelect);
                break;
            case "11":
                enableFields(
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                disableFields(kategoriSelect, hukumSelect);
                break;
            default:
                enableFields(
                    kategoriSelect,
                    hukumSelect,
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                break;
        }
    }

    // --- reset many field ---
    function resetFields(fields) {
        fields.forEach((field) => {
            field.value = "";
        });
    }

    // =============================
    // Event Listener
    // =============================

    // --- Kalimat Changed ---
    kalimatSelect.addEventListener("change", () => {
        // set translation to required
        translation.setAttribute("required", "required");
        const currentKalimat = kalimatSelect.value.trim();
        if (currentKalimat === "11" || currentKalimat === "41") {
            fieldsController();
            resetFields([
                kategoriSelect,
                hukumSelect,
                irobSelect,
                tandaSelect,
                kedudukanSelect,
                simbolSelect,
            ]);
            populateKedudukan(["10", "41"]);
            fillIrob();
            return;
        }

        fieldsController();
        resetFields([
            kategoriSelect,
            hukumSelect,
            irobSelect,
            tandaSelect,
            kedudukanSelect,
            simbolSelect,
        ]);
        populateCategory();
    });

    // --- Kategori Changed ---
    kategoriSelect.addEventListener("change", () => {
        fillHukum();

        const currentKalimat = kalimatSelect.value.trim();
        if (currentKalimat === "10" || currentKalimat === "22") {
            populateKedudukan(currentKalimat);
        }
        if (currentKalimat === "42") {
            populateKedudukan(["10", "41"]);
        }

        resetFields([irobSelect, tandaSelect, simbolSelect]);
        fillSimbol();
    });

    // --- Irob Changed ---
    irobSelect.addEventListener("change", fillTanda);

    // --- Kedudukan Changed ---
    kedudukanSelect.addEventListener("change", () => {
        const currentKedudukan = kedudukanSelect.value;
        if (currentKedudukan === "KD53" || currentKedudukan === "KD58") {
            disableFields(irobSelect, tandaSelect);
        } else if (currentKedudukan === "KD4" || currentKedudukan === "KD6") {
            disableFields(hukumSelect, irobSelect, tandaSelect);
        } else if (currentKedudukan === "KD59") {
            disableFields(irobSelect, tandaSelect);
        } else {
            enableFields(irobSelect, tandaSelect);
            fillIrob();
            fillTanda();
        }
        fillSimbol();
    });
});
