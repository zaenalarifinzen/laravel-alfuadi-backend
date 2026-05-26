/* ======================================================
   NAHWU FORM ENGINE 
   ====================================================== */

const MasterData = {
    raw: null,
    loadingPromise: null,

    async load() {
        if (this.raw) return this.raw;

        if (!this.loadingPromise) {
            this.loadingPromise = fetch("/words/data/data-nahwu")
                .then((res) => res.json())
                .then((data) => {
                    this.raw = data;               
                    return data;                    
                });
        }

        return this.loadingPromise;
    },
};

/* ======================================================
   SEARCHABLE DROPDOWN COMPONENT
   ====================================================== */

class SearchableDropdown {
    constructor(selectElement) {
        this.select = selectElement;
        this.name = selectElement.name;
        this.isRequired = this.select.hasAttribute("required");
        this.select.removeAttribute("required");

        this.placeholder =
            selectElement.getAttribute("placeholder") || `Pilih ${this.name}`;

        this.buildHTML();
        this.cacheElements();
        this.init();
    }

    buildHTML() {
        this.select.style.display = "none";

        this.wrapper = document.createElement("div");
        this.wrapper.classList.add("custom-dropdown");

        if (this.select.disabled) {
            this.wrapper.classList.add("disabled");
        }

        this.wrapper.innerHTML = `
            <div class="select-btn">
                <span>${this.placeholder}</span>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div class="content">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari">
                </div>
                <ul class="options"></ul>
            </div>
            <small class="error-message"></small>
        `;

        this.select.after(this.wrapper);
    }

    cacheElements() {
        this.selectBtn = this.wrapper.querySelector(".select-btn");
        this.searchInput = this.wrapper.querySelector(".search input");
        this.optionsContainer = this.wrapper.querySelector(".options");
        this.displaySpan = this.selectBtn.querySelector("span");
        this.errorMessage = this.wrapper.querySelector(".error-message");
    }

    async init() {
        await MasterData.load();
        this.data = this.buildInitialData();
        this.populateSelect();
        this.renderOptions();
        this.bindEvents();
    }

    buildInitialData() {
        const data = MasterData.raw;

        switch (this.name) {
            case "kalimat":
                return data.kalimat.map((i) => ({
                    value: i.id,
                    label_in: i.kalimat_in,
                    label_ar: i.kalimat_ar,
                    label: i.kalimat_ar_musyakal,
                }));

            case "kategori":
                return data.kategori.map((i) => ({
                    value: i.id,
                    label_in: i.kategori_in,
                    label_ar: i.kategori_ar,
                    label: i.kategori_ar_musyakal,
                }));

            case "kedudukan":
                return data.kedudukan.map((i) => ({
                    value: i.id,
                    label_in: i.kedudukan_in,
                    label_ar: i.kedudukan_ar,
                    label: i.kedudukan_ar_musyakal,
                }));

            case "hukum":
                return [...new Set(data.kategori.map((k) => k.hukum))]
                    .filter(Boolean)
                    .map((h) => ({
                        value: h,
                        label_in: "",
                        label_ar: h,
                        label: h,
                    }));

            case "irob":
                return [...new Set(data.kedudukan.map((k) => k.irob))]
                    .filter(Boolean)
                    .map((i) => ({
                        value: i,
                        label_in: i,
                        label_ar: i,
                        label: i,
                    }));

            case "tanda":
                const tandaSet = new Set();

                data.kategori.forEach((k) => {
                    [k.rofa, k.nashob, k.jar, k.jazm]
                        .map((val) => (val ? val.trim() : ""))
                        .filter((val) => val !== "")
                        .forEach((val) => tandaSet.add(val));
                });

                return Array.from(tandaSet).map((t) => ({
                    value: t,
                    label_in: t,
                    label_ar: t,
                    label: t,
                }));

            case "simbol":
                return [...new Set(data.kedudukan.map((k) => k.simbol))]
                    .filter(Boolean)
                    .map((s) => ({
                        value: s,
                        label_in: s,
                        label_ar: s,
                        label: s,
                    }));

            default:
                return [];
        }
    }

    populateSelect() {
        this.select.innerHTML = `<option value="">${this.placeholder}</option>`;
        this.data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.value;
            option.textContent = item.label;
            this.select.appendChild(option);
        });
    }

    renderOptions(filtered = null) {
        const dataset = filtered || this.data;
        this.optionsContainer.innerHTML = "";

        if (!dataset || dataset.length === 0) {
            const searchVal = this.searchInput.value.trim();
            this.optionsContainer.innerHTML = `<span>Data tidak ditemukan.</span>`;

            // Custom option
            if (searchVal) {
                const addNew = document.createElement("li");
                addNew.classList.add("add-new-option");
                addNew.innerHTML = `Tambah "<b class=>${searchVal}</b>"`;
                addNew.addEventListener("click", () => {
                    this.addNewOption(searchVal);
                });
                this.optionsContainer.appendChild(addNew);
            }
            return;
        }

        dataset.forEach((item) => {
            const li = document.createElement("li");
            li.classList.add("ar");
            li.textContent = item.label;
            li.dataset.value = item.value;

            if (item.value == this.select.value) {
                li.classList.add("selected");
            }

            li.addEventListener("click", () => {
                this.setValue(item.value);
            });

            this.optionsContainer.appendChild(li);
        });
    }

    addNewOption(value) {
        // if exist
        const exist = this.data.find(
            (d) => d.value.toLowerCase() === value.toLowerCase(),
        );
        if (exist) {
            this.setValue(exist.value, false, true);
            return;
        }

        const newItem = {
            value: value,
            label: value,
            label_ar: value,
            label_in: value,
        };
        this.data.push(newItem);

        const option = document.createElement("option");
        option.value = value;
        option.textContent = value;
        this.select.appendChild(option);

        this.setValue(value, false, true);

        this.searchInput.value = "";
        this.renderOptions();
    }

    setValue(value, silent = false, skipChange = false) {
        this.select.value = value;

        const selectedOption = this.select.options[this.select.selectedIndex];

        this.displaySpan.textContent =
            selectedOption?.textContent || this.placeholder;

        this.wrapper.classList.remove("active");
        this.displaySpan.classList.add("ar");

        if (!silent) this.validate();

        this.renderOptions();
        if (!skipChange) {
            this.select.dispatchEvent(
                new CustomEvent("change", { detail: { isRestoring: true } }),
            );
        }
    }

    setValueById(value) {
        if (!value) return;

        this.select.value = value;
        const selectedOption = this.select.options[this.select.selectedIndex];

        if (selectedOption && selectedOption.value) {
            const dataItem = this.data.find((d) => d.value == value);

            if (dataItem) {
                this.displaySpan.textContent = dataItem.label;
            } else {
                this.displaySpan.textContent = selectedOption.textContent;
            }

            this.displaySpan.classList.add("ar");
        } else {
            const option = document.createElement("option");
            option.value = value;
            option.textContent = value;
            this.select.appendChild(option);

            this.data.push({
                value: value,
                label: value,
                label_ar: value,
                label_in: value,
            });

            this.select.value = value;
            this.displaySpan.textContent = value;
            this.displaySpan.classList.add("ar");
        }

        this.wrapper.classList.remove("invalid");
        this.renderOptions();
    }

    setData(newData) {
        this.data = newData;
        this.searchInput.value = "";
        this.populateSelect();
        this.renderOptions();
    }

    disable() {
        this.select.disabled = true;
        this.wrapper.classList.add("disabled");
        this.select.value = "";
        this.displaySpan.textContent = "";

        this.wrapper.classList.remove("invalid");
        this.displaySpan.classList.remove("ar");

        // remove arrow icon
        const icon = this.selectBtn.querySelector("i");
        if (icon) icon.style.display = "none";

        this.isRequired = false;
    }

    enable() {
        this.select.disabled = false;
        this.wrapper.classList.remove("disabled");
        this.wrapper.classList.remove("invalid");

        // remove error message
        this.errorMessage.textContent = "";

        if (!this.select.value) {
            this.displaySpan.textContent = this.placeholder;
            this.displaySpan.classList.remove("ar");
        }

        // show arrow icon
        const icon = this.selectBtn.querySelector("i");
        if (icon) icon.style.display = "block";

        this.isRequired = true;
    }

    validate() {
        if (!this.isRequired) return true;

        if (!this.select.value) {
            this.wrapper.classList.add("invalid");
            this.errorMessage.textContent = "Wajib diisi";
            return false;
        }

        this.wrapper.classList.remove("invalid");
        this.errorMessage.textContent = "";
        return true;
    }

    bindEvents() {
        this.selectBtn.addEventListener("click", () => {
            if (this.select.disabled) return;
            this.wrapper.classList.toggle("active");
        });

        this.searchInput.addEventListener("keyup", () => {
            const val = this.searchInput.value.toLowerCase();

            const filtered = this.data.filter((d) =>
                [d.label, d.label_ar, d.label_in]
                    .filter(Boolean)
                    .some((label) => label.toLowerCase().includes(val)),
            );

            this.renderOptions(filtered);
        });

        window.addEventListener("click", (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.wrapper.classList.remove("active");
                this.searchInput.value = "";
                this.renderOptions();
            }
        });
    }
}

/* ======================================================
   RELATIONAL LOGIC ENGINE
   ====================================================== */

class NahwuFormController {
    constructor(options = {}) {
        this.options = Object.assign({ autoFill: true }, options);
        this.init();
    }

    async init() {
        await MasterData.load();

        this.instances = {};
        document
            .querySelectorAll("select.custom-dropdown")
            .forEach((select) => {
                const instance = new SearchableDropdown(select);
                this.instances[select.name] = instance;
            });

        this.instances.kategori?.disable();
        this.instances.kedudukan?.disable();
        this.instances.hukum?.disable();
        this.instances.irob?.disable();
        this.instances.tanda?.disable();
        this.instances.simbol?.disable();

        this.bindKalimatRelations();
        this.bindHukumRelations();
        this.bindKategoriRelations();

        const autoFillEnabled = this.options.autoFill && this.isAutoFillAllowedOnPage();
        if (autoFillEnabled) this.bindAutoFill();
    }

    isAutoFillAllowedOnPage() {
        // Priority: body[data-nahwu-autofill], then form[data-nahwu-autofill], default true
        try {
            const bodyFlag = document.body?.dataset?.nahwuAutofill;
            if (typeof bodyFlag !== "undefined") return bodyFlag !== "false";

            const form = document.getElementById("form-add-word");
            const formFlag = form?.dataset?.nahwuAutofill;
            if (typeof formFlag !== "undefined") return formFlag !== "false";
        } catch (err) {
            // ignore and allow autofill by default
        }

        return true;
    }

    bindKalimatRelations() {
        const kalimat = this.instances.kalimat;

        if (!kalimat) return;

        kalimat.select.addEventListener("change", (e) => {
            const isRestoring = e.detail?.isRestoring || false;

            if (!isRestoring) {
                this.resetDropdown(this.instances.hukum);
                this.resetDropdown(this.instances.kategori);
                this.resetDropdown(this.instances.kedudukan);
                this.resetDropdown(this.instances.irob);
                this.resetDropdown(this.instances.tanda);
                this.resetDropdown(this.instances.simbol);
            }

            const selected = kalimat.select.value;
            
            this.updateHukumOptions(selected);
            this.prepareFieldsAfterKalimatSelection();
            this.applyKalimatFieldRules(selected);
        });
    }

    bindHukumRelations() {
        const hukum = this.instances.hukum;
        
        if (!hukum) return;

        hukum.select.addEventListener("change", (e) => {
            const isRestoring = e.detail?.isRestoring || false;

            if (!isRestoring) {
                this.resetDropdown(this.instances.kategori);
                this.resetDropdown(this.instances.kedudukan);
                this.resetDropdown(this.instances.irob);
                this.resetDropdown(this.instances.tanda);
                this.resetDropdown(this.instances.simbol);
            }

            const selectedKalimat = this.instances.kalimat?.select.value;
            const selectedHukum = hukum.select.value;

            this.updateKategoriOptions(selectedKalimat, selectedHukum);
            this.instances.kategori?.enable();
            this.instances.kedudukan?.disable();
            this.instances.irob?.disable();
            this.instances.tanda?.disable();
            this.instances.simbol?.disable();
        });
    }

    bindKategoriRelations() {
        const kategori = this.instances.kategori;
        if (!kategori) return;

        kategori.select.addEventListener("change", (e) => {
            const isRestoring = e.detail?.isRestoring || false;

            if (!isRestoring) {
                this.resetDropdown(this.instances.kedudukan);
                this.resetDropdown(this.instances.irob);
                this.resetDropdown(this.instances.tanda);
                this.resetDropdown(this.instances.simbol);
            }

            const selectedKalimat = this.instances.kalimat?.select.value;
            if (!selectedKalimat) return;

            // for Jumlah or Sibhul Jumlah filter from Isim
            if (selectedKalimat === "41" || selectedKalimat === "42") {
                this.updateKedudukanOptions("10");
            }

            this.updateKedudukanOptions(selectedKalimat);
            
            // for Fiil Madhi and Amr
            if (selectedKalimat !== "21" && selectedKalimat !== "23") {
                this.instances.kedudukan?.enable();
            }
            this.instances.simbol?.enable();
        });
    }

    // =============================================================
    // Updater Data Options
    // =============================================================

    updateHukumOptions(selectedKalimat) {
        const hukum = this.instances.hukum;
        if (!hukum) return;

        const data = MasterData.raw;
        const filteredHukum = [...new Set(data.kategori
            .filter((k) => k.id_kalimat === selectedKalimat)
            .map((k) => k.hukum))]
            .filter(Boolean)
            .map((h) => this.createOptionItem(h, h, h, h));

        hukum.setData(filteredHukum);
    }

    updateKategoriOptions(selectedKalimat, selectedHukum) {
        const kategori = this.instances.kategori;
        if (!kategori) return;

        const data = MasterData.raw;

        const filteredKategori = data.kategori
            .filter((k) => k.id_kalimat === selectedKalimat && k.hukum === selectedHukum)
            .map((k) => this.createOptionItem(k.id, k.kategori_ar_musyakal, k.kategori_ar, k.kategori_in));

        kategori.setData(filteredKategori);
    }

    updateKedudukanOptions(selectedKalimat) {
        const kedudukan = this.instances.kedudukan;
        if (!kedudukan) return;

        const data = MasterData.raw;
        const candidates = ["41", "42", "50"].includes(selectedKalimat)
            ? data.kedudukan.filter((k) => ["10", "41"].includes(k.id_kalimat))
            : data.kedudukan.filter((k) => k.id_kalimat === selectedKalimat);

        const filteredKedudukan = candidates.map((k) =>
            this.createOptionItem(k.id, k.kedudukan_ar_musyakal, k.kedudukan_ar, k.kedudukan_in),
        );

        kedudukan.setData(filteredKedudukan);
    }

    prepareFieldsAfterKalimatSelection() {
        this.instances.hukum?.enable();
        this.instances.kategori?.disable();
        this.instances.kedudukan?.disable();
        this.instances.irob?.disable();
        this.instances.tanda?.disable();
        this.instances.simbol?.disable();
    }

    createOptionItem(value, label, label_ar = "", label_in = "") {
        return { value, label, label_ar, label_in };
    }

    enableAllRelationFields() {
        this.instances.kategori?.enable();
        this.instances.kedudukan?.enable();
        this.instances.hukum?.enable();
        this.instances.irob?.enable();
        this.instances.tanda?.enable();
        this.instances.simbol?.enable();
    }

    applyKalimatFieldRules(selected) {
        const FIELD_RULES = {
            21: ["kedudukan", "irob", "tanda"],
            23: ["kedudukan", "irob", "tanda"],
            30: ["kedudukan", "irob", "tanda"],
            41: ["kategori", "hukum"],
            42: ["hukum"],
            50: ["kategori", "hukum"],
        };

        const fieldToDisable = FIELD_RULES[selected] || [];
        fieldToDisable.forEach((name) => {
            this.instances[name]?.disable();
        });
    }

    bindAutoFill() {
        const kategori = this.instances.kategori;
        const kedudukan = this.instances.kedudukan;

        kategori?.select.addEventListener("change", (e) => this.handleKategoriChange(e));
        kedudukan?.select.addEventListener("change", (e) => this.handleKedudukanChange(e));
    }

    handleKategoriChange(e) {
        const isRestoring = e.detail?.isRestoring || false;

        if (!isRestoring) {
            this.resetDropdown(this.instances.kedudukan);
            this.resetDropdown(this.instances.hukum);
            this.resetDropdown(this.instances.irob);
            this.resetDropdown(this.instances.tanda);
            this.resetDropdown(this.instances.simbol);
        }

        const selectedKategori = this.getKategoriById(this.instances.kategori?.select.value);
        if (!selectedKategori) return;

        this.autoFillSimbolByKategori(selectedKategori);
    }

    handleKedudukanChange(e) {
        const isRestoring = e.detail?.isRestoring || false;

        if (!isRestoring) {
            this.resetDropdown(this.instances.irob);
            this.resetDropdown(this.instances.tanda);
        }

        const selectedKedudukan = this.getKedudukanById(this.instances.kedudukan?.select.value);
        if (!selectedKedudukan) return;

        if (selectedKedudukan.id === "KD4101" || selectedKedudukan.id === "KD4102") {
            this.instances.irob?.disable();
            this.instances.tanda?.disable();
            return;
        }

        if (selectedKedudukan.id === "KD1006") {
            this.instances.hukum?.disable();
            this.instances.irob?.disable();
            this.instances.tanda?.disable();
            return;
        }

        if (selectedKedudukan.id === "KD1056") {
            this.instances.irob?.disable();
            this.instances.tanda?.disable();
            return;
        }

        this.instances.irob?.enable();
        this.instances.tanda?.enable();

        if (this.instances.irob && selectedKedudukan.irob) {
            this.instances.irob.setData([
                this.createOptionItem(selectedKedudukan.irob, selectedKedudukan.irob),
            ]);
            this.instances.irob.setValue(selectedKedudukan.irob, true);
        }

        const selectedKategori = this.getKategoriForTanda(this.instances.kalimat?.select.value);
        if (selectedKategori && this.instances.tanda) {
            const tandaList = [
                selectedKategori.rofa,
                selectedKategori.nashob,
                selectedKategori.jar,
                selectedKategori.jazm,
            ]
                .filter(Boolean)
                .map((val) => this.createOptionItem(val, val));

            this.instances.tanda.setData(tandaList);

            let tandaIrob = "";
            const currentIrob = this.instances.irob?.data?.[0]?.value || "";

            switch (currentIrob) {
                case "مَرْفُوْعٌ":
                    tandaIrob = selectedKategori.rofa;
                    break;
                case "مَنْصُوْبٌ":
                    tandaIrob = selectedKategori.nashob;
                    break;
                case "مَجْرُوْرٌ":
                    tandaIrob = selectedKategori.jar;
                    break;
                case "مَجْزُوْمٌ":
                    tandaIrob = selectedKategori.jazm;
                    break;
            }

            this.instances.tanda.setValue(tandaIrob, true);
        }

        if (this.instances.hukum?.select.value.trim() === "مُعْرَبٌ" || this.instances.kalimat?.select.value !== "22") {
            if (this.instances.simbol && selectedKedudukan.simbol) {
                this.instances.simbol.setData([
                    this.createOptionItem(selectedKedudukan.simbol, selectedKedudukan.simbol),
                ]);
                this.instances.simbol.setValue(selectedKedudukan.simbol);
            }
        }
    }

    autoFillSimbolByKategori(selectedKategori) {
        if (!this.instances.hukum || !selectedKategori.hukum) return;

        // this.instances.hukum.setData([
        //     this.createOptionItem(selectedKategori.hukum, selectedKategori.hukum),
        // ]);
        // this.instances.hukum.setValue(selectedKategori.hukum, true);

        const kalimatId = selectedKategori.id_kalimat;
        if (
            kalimatId === "21" ||
            kalimatId === "23" ||
            kalimatId === "30" ||
            (kalimatId === "22" && this.instances.hukum.data?.[0]?.value !== "مُعْرَبٌ")
        ) {
            if (this.instances.simbol && selectedKategori.simbol) {
                this.instances.simbol.setData([
                    this.createOptionItem(selectedKategori.simbol, selectedKategori.simbol),
                ]);
                this.instances.simbol.setValue(selectedKategori.simbol, true);
            }
        }
    }

    getKategoriById(id) {
        const data = MasterData.raw;
        return data.kategori.find((k) => k.id == id) || null;
    }

    getKedudukanById(id) {
        const data = MasterData.raw;
        return data.kedudukan.find((k) => k.id == id) || null;
    }

    getKategoriForTanda(kalimatId) {
        const data = MasterData.raw;

        if (kalimatId !== "50" && kalimatId !== "41") {
            return data.kategori.find((k) => k.id == this.instances.kategori?.select.value) || null;
        }

        return data.kategori.find((k) => k.id === "C1008") || null;
    }

    bindFormValidation() {
        const form = document.getElementById("form-add-word");
        console.log("Form detected: ", form);

        if (!form) return;

        form.setAttribute("novalidate", true);

        form.addEventListener("submit", (e) => {
            let valid = true;

            Object.values(this.instances).forEach((instance) => {
                if (!instance.validate()) valid = false;
            });

            if (!valid) e.preventDefault();
        });
    }

    resetDropdown(instance) {
        if (!instance) return;

        instance.setValue("", true);

        if (!instance.select.disabled) {
            instance.displaySpan.textContent = instance.placeholder;
        } else {
            instance.displaySpan.textContent = "";
        }

        instance.wrapper.classList.remove("invalid");
        instance.displaySpan.classList.remove("ar");
    }

    // =============================================================
    // - Helper to search kalimat_id, kategori_id and kedudukan_id
    // - This method to fix data in older submitted
    // =============================================================

    resolveIds(word) {
        const data = MasterData.raw;

        // If ID is already
        if (word.kalimat_id && word.kategori_id && word.kedudukan_id) {
            return {
                kalimat_id: word.kalimat_id,
                kategori_id: word.kategori_id,
                kedudukan_id: word.kedudukan_id,
            };
        }

        // Lookup kalimat_id from text
        let kalimat_id = word.kalimat_id || null;
        if (!kalimat_id && word.kalimat) {
            const found = data.kalimat.find(
                (k) => k.kalimat_ar_musyakal === word.kalimat,
            );
            kalimat_id = found?.id || null;
        }

        // Lookup kategori_id from text
        let kategori_id = word.kategori_id || null;
        if (!kategori_id && word.kategori) {
            const found = data.kategori.find(
                (k) => k.kategori_ar_musyakal === word.kategori,
            );
            kategori_id = found?.id || null;
        }

        // Lookup kedudukan_id from text
        let kedudukan_id = word.kedudukan_id || null;
        if (!kedudukan_id && word.kedudukan) {
            const found = data.kedudukan.find(
                (k) => k.kedudukan_ar_musyakal === word.kedudukan,
            );
            kedudukan_id = found?.id || null;
        }

        return { kalimat_id, kategori_id, kedudukan_id };
    }
}

/* ======================================================
   INITIALIZE
   ====================================================== */

document.addEventListener("DOMContentLoaded", () => {
    const bodyFlag = document.body?.dataset?.nahwuAutofill;
    const autoFill = typeof bodyFlag !== "undefined" ? bodyFlag !== "false" : true;

    window.nahwuFormController = new NahwuFormController({ autoFill });
});
