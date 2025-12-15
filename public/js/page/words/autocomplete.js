document.addEventListener("DOMContentLoaded", async () => {
    // --- Load JSON Data ---
    const response = await fetch("/json/data-nahwu.json");
    const data = await response.json();

    // --- Get Form Element ---
    const kalimatSelect = document.getElementById("input-kalimat");
    const kategoriSelect = document.getElementById("input-kategori");
    const hukumSelect = document.getElementById("input-hukum");
    const irobSelect = document.getElementById("input-irob");
    const tandaSelect = document.getElementById("input-tanda");
    const kedudukanSelect = document.getElementById("input-kedudukan");
    const simbolSelect = document.getElementById("input-simbol");

    const disableFields = (...fields) =>
        fields.forEach((f) => (f.disabled = true));
    const enableFields = (...fields) =>
        fields.forEach((f) => (f.disabled = false));

    // disableFields(kategoriSelect, hukumSelect, irobSelect, tandaSelect, kedudukanSelect, simbolSelect);

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

        // enableFields(kategoriSelect);
    }

    // --- isi dropdown Kedudukan ---
    function populateKedudukan() {
        const selectedKalimat = kalimatSelect.value.trim();

        const filteredKedudukan = data.kedudukan.filter(
            (k) => k.id_kalimat === selectedKalimat
        );
        kedudukanSelect.innerHTML =
            "<option selected disabled>Pilih Kedudukan</option>";
        filteredKedudukan.forEach((k) => {
            kedudukanSelect.innerHTML += `<option value="${k.id}">${k.kedudukan_ar}</option>`;
        });
    }

    // --- isi Hukum ---
    function fillHukum() {
        const kategori = data.kategori.find(
            (k) => k.id == kategoriSelect.value
        );
        if (!kategori) return;

        hukumSelect.innerHTML = `<option selected>${kategori.hukum}</option>`;
        // add too other unique hukum in dropdown based on kalimat without duplicating
        const selectedKalimat = kalimatSelect.value.trim();
        const filteredKategori = data.kategori.filter(
            (k) => k.id_kalimat === selectedKalimat
        );

        const uniqueHukum = new Set();
        filteredKategori.forEach((k) => {
            if (k.hukum !== kategori.hukum) {
                uniqueHukum.add(k.hukum);
            }
        });
        uniqueHukum.forEach((h) => {
            hukumSelect.innerHTML += `<option>${h}</option>`;
        });
    }

    // --- isi i'rob ---
    function fillIrob() {
        const selectedKedudukan = kedudukanSelect.value;
        const kd = data.kedudukan.find((k) => k.id == selectedKedudukan);
        if (!kd) return;

        irobSelect.innerHTML = `<option selected>${kd.irob}</option>`;
        // add too other unique irob in dropdown based on kalimat without duplicating
        const selectedKalimat = kalimatSelect.value.trim();
        const filteredKedudukan = data.kedudukan.filter(
            (k) => k.id_kalimat === selectedKalimat
        );
        const uniqueIrob = new Set();
        filteredKedudukan.forEach((k) => {
            if (k.irob !== kd.irob) {
                uniqueIrob.add(k.irob);
            }
        });
        uniqueIrob.forEach((i) => {
            irobSelect.innerHTML += `<option>${i}</option>`;
        });
    }

    // --- isi Tanda I‘rob ---
    function fillTanda() {
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

        tandaSelect.innerHTML = tanda
            ? `<option selected>${tanda}</option>`
            : "<option selected></option>";
        // add too other unique tanda in dropdown based on kalimat without duplicating
        const selectedKalimat = kalimatSelect.value.trim();
        const filteredKategori = data.kategori.filter(
            (k) => k.id_kalimat === selectedKalimat
        );

        const uniqueTanda = new Set();
        filteredKategori.forEach((k) => {
            let otherTanda = "";
            switch (irob) {
                case "مَرْفُوْعٌ":
                    otherTanda = k.rofa;
                    break;
                case "مَنْصُوْبٌ":
                    otherTanda = k.nashob;
                    break;
                case "مَجْرُوْرٌ":
                    otherTanda = k.jar;
                    break;
                case "مَجْزُوْمٌ":
                    otherTanda = k.jazm;
                    break;
            }
            if (otherTanda && otherTanda !== tanda) {
                uniqueTanda.add(otherTanda);
            }
        });
        uniqueTanda.forEach((t) => {
            tandaSelect.innerHTML += `<option>${t}</option>`;
        });
    }

    // --- isi simbol ---
    function fillSimbol() {
        const selectedKedudukan = kedudukanSelect.value;
        const kd = data.kedudukan.find((k) => k.id == selectedKedudukan);
        if (!kd) return;

        simbolSelect.innerHTML = `<option selected>${kd.simbol}</option>`;
    }

    // --- Fields Controller ---
    function fieldsController() {
        const currentKalimat = kalimatSelect.value;

        switch (currentKalimat) {
            case "2.1":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "2.3":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "3.0":
                enableFields(kategoriSelect, hukumSelect, simbolSelect);
                disableFields(kedudukanSelect, irobSelect, tandaSelect);
                break;
            case "4.1":
                enableFields(
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                disableFields(kategoriSelect, hukumSelect);
                break;
            case "4.2":
                enableFields(
                    kategoriSelect,
                    kedudukanSelect,
                    irobSelect,
                    tandaSelect,
                    simbolSelect
                );
                disableFields(hukumSelect);
                break;
            case "5.0":
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

    // --- Validate before submit ---
    const form = document.getElementById("form-add-word");
    form.addEventListener("submit", (e) => {
        const kalimat = kalimatSelect.value;
        const kategori = kategoriSelect.value;
        if (!kalimat || !kategori) {
            e.preventDefault();
            alert("Kalimat dan kategori wajib diisi!");
        }
    });

    // =============================
    // Event Listener
    // =============================
    kalimatSelect.addEventListener("change", () => {
        populateCategory();
        fieldsController();
        resetFields([
            hukumSelect,
            irobSelect,
            tandaSelect,
            kedudukanSelect,
            simbolSelect,
        ]);
    });

    kategoriSelect.addEventListener("change", () => {
        fillHukum();

        const currentKalimat = kalimatSelect.value.trim();
        if (currentKalimat === "1.0" || currentKalimat === "2.2") {
            populateKedudukan();
        }

        resetFields([irobSelect, tandaSelect, simbolSelect]);
    });

    irobSelect.addEventListener("change", fillTanda);

    kedudukanSelect.addEventListener("change", () => {
        fillIrob();
        fillTanda();
        fillSimbol();
    });
});
