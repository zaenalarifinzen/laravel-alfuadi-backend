/* ======================================================
   NAHWU FORM ENGINE 
   ====================================================== */

const MasterData = {
    raw: null,
    loadingPromise: null,

    async load() {
        if (this.raw) return this.raw;

        if (!this.loadingPromise) {
            this.loadingPromise = fetch("/json/data-nahwu.json")
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
   CUSTOM DROPDOWN COMPONENT
   ====================================================== */

class CustomDropdown {
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
                    label: i.kalimat_ar,
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
            this.optionsContainer.innerHTML = `<span>Data tidak ditemukan</span>`;
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

    setValue(value) {
        this.select.value = value;

        const selectedOption = this.select.options[this.select.selectedIndex];

        this.displaySpan.textContent =
            selectedOption?.textContent || this.placeholder;

        this.wrapper.classList.remove("active");
        this.displaySpan.classList.add("ar");
        this.validate();
        this.renderOptions();

        this.select.dispatchEvent(new Event("change"));
    }

    setData(newData) {
        this.data = newData;
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
    }

    enable() {
        this.select.disabled = false;
        this.wrapper.classList.remove("disabled");

        if (!this.select.value) {
            this.displaySpan.textContent = this.placeholder;
        }

        // show arrow icon
        const icon = this.selectBtn.querySelector("i");
        if (icon) icon.style.display = "block";
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
    constructor() {
        this.init();
    }

    async init() {
        await MasterData.load();

        this.instances = {};
        document
            .querySelectorAll("select.custom-dropdown")
            .forEach((select) => {
                const instance = new CustomDropdown(select);
                this.instances[select.name] = instance;
            });

        this.instances.kategori?.disable();
        this.instances.kedudukan?.disable();
        this.instances.hukum?.disable();
        this.instances.irob?.disable();
        this.instances.tanda?.disable();
        this.instances.simbol?.disable();

        this.bindRelations();
        this.bindAutoFill();
        this.bindFormValidation();
    }

    bindRelations() {
        const data = MasterData.raw;

        const kalimat = this.instances.kalimat;
        const kategori = this.instances.kategori;
        const kedudukan = this.instances.kedudukan;

        if (!kalimat) return;

        kalimat.select.addEventListener("change", () => {
            this.resetDropdown(this.instances.kategori);
            this.resetDropdown(this.instances.kedudukan);
            this.resetDropdown(this.instances.hukum);
            this.resetDropdown(this.instances.irob);
            this.resetDropdown(this.instances.tanda);
            this.resetDropdown(this.instances.simbol);

            const selected = kalimat.select.value;
            // debug
            console.log("Kalimat id: ", selected);

            const filteredKategori = data.kategori
                .filter((k) => k.id_kalimat === selected)
                .map((k) => ({
                    value: k.id,
                    label: k.kategori_ar_musyakal,
                    label_ar: k.kategori_ar,
                    label_in: k. kategori_in,
                }));

            kategori?.setData(filteredKategori);

            // Special case for id_kalimat 41 and 50 (jumlah & isim muawwal)
            if (selected === "41" || selected === "50") {
                const filteredKedudukan = data.kedudukan
                    .filter((k) => ["10", "41"].includes(k.id_kalimat))
                    .map((k) => ({
                        value: k.id,
                        label: k.kedudukan_ar_musyakal,
                        label_ar: k.kedudukan_ar,
                        label_in: k.kedudukan_in,
                    }));
                kedudukan?.setData(filteredKedudukan);
            } else {
                const filteredKedudukan = data.kedudukan
                    .filter((k) => k.id_kalimat === selected)
                    .map((k) => ({
                        value: k.id,
                        label: k.kedudukan_ar_musyakal,
                        label_ar: k.kedudukan_ar,
                        label_in: k.kedudukan_in,
                    }));

                kedudukan?.setData(filteredKedudukan);
            }

            // ====================
            // FIELD CONTROLLER
            // ====================

            // Enable all first
            this.instances.kategori?.enable();
            this.instances.kedudukan?.enable();
            this.instances.hukum?.enable();
            this.instances.irob?.enable();
            this.instances.tanda?.enable();
            this.instances.simbol?.enable();

            // Rule Config (disable fields based on selected kalimat)
            const FIELD_RULES = {
                21: ["kedudukan", "irob", "tanda"],
                23: ["kedudukan", "irob", "tanda"],
                30: ["kedudukan", "irob", "tanda"],
                41: ["kategori", "hukum"],
                42: ["hukum"],
                11: ["kategori", "hukum"],
            };

            const fieldToDisable = FIELD_RULES[selected] || [];

            fieldToDisable.forEach((name) => {
                this.instances[name]?.disable();
            });
        });
    }

    bindAutoFill() {
        const data = MasterData.raw;

        const kategori = this.instances.kategori;
        const kedudukan = this.instances.kedudukan;
        const hukum = this.instances.hukum;
        const irob = this.instances.irob;
        const tanda = this.instances.tanda;
        const simbol = this.instances.simbol;

        // ==========================
        // KATEGORI -> HUKUM + TANDA
        // ==========================
        kategori?.select.addEventListener("change", () => {
            const kategoriInstance = this.instances.kategori;
            const selectedKategori = data.kategori.find(
                (k) => k.id == kategoriInstance.select.value,
            );

            if (!selectedKategori) return;

            // Hukum
            if (hukum && selectedKategori.hukum) {
                hukum.setData([
                    {
                        value: selectedKategori.hukum,
                        label: selectedKategori.hukum,
                    },
                ]);
                hukum.setValue(selectedKategori.hukum);

                // if kalimat is fiil madhi (21) or fiil amr (23) or 30 (huruf) or mudhori mabni, set simbol
                const kalimatId = selectedKategori.id_kalimat;
                if (
                    kalimatId === "21" ||
                    kalimatId === "23" ||
                    kalimatId === "30" ||
                    (kalimatId === "22" &&
                        hukum.data?.[0]?.value !== "مُعْرَبٌ")
                ) {
                    simbol.setData([
                        {
                            value: selectedKategori.simbol,
                            label: selectedKategori.simbol,
                        },
                    ]);
                    simbol.setValue(selectedKategori.simbol);
                }
            }
        });

        // ==========================
        // KEDUDUKAN -> IROB + TANDA + SIMBOL
        // ==========================

        kedudukan?.select.addEventListener("change", () => {
            const selectedKedudukan = data.kedudukan.find(
                (k) => k.id == kedudukan.select.value,
            );

            if (!selectedKedudukan) return;

            if (
                selectedKedudukan.id === "KD4101" ||
                selectedKedudukan.id === "KD4102"
            ) {
                // if jumlah or sibhul jumlah, disable irob dan tanda
                this.instances.irob?.disable();
                this.instances.tanda?.disable();
            } else if (selectedKedudukan.id === "KD1006") {
                // if fail mustatir, disable hukum, irob and tanda
                this.instances.hukum?.disable();
                this.instances.irob?.disable();
                this.instances.tanda?.disable();
            } else if (selectedKedudukan.id === "KD1056") {
                // if dhomir fashl, disable irob and tanda
                this.instances.irob?.disable();
                this.instances.tanda?.disable();
            } else {
                this.instances.irob?.enable();
                this.instances.tanda?.enable();

                if (irob && selectedKedudukan.irob) {
                    irob.setData([
                        {
                            value: selectedKedudukan.irob,
                            label: selectedKedudukan.irob,
                        },
                    ]);
                    irob.setValue(selectedKedudukan.irob);
                }

                // Tanda
                const kalimatId = this.instances.kalimat?.select.value;
                const kategoriInstance = this.instances.kategori;
                let selectedKategori = null;

                if (kalimatId !== "50" && kalimatId !== "41") {
                    selectedKategori = data.kategori.find(
                        (k) => k.id == kategoriInstance.select.value,
                    );
                } else {
                    selectedKategori = data.kategori.find(
                        (k) => k.id === "C1008",
                    );
                }

                if (tanda) {
                    const tandaList = [
                        selectedKategori.rofa,
                        selectedKategori.nashob,
                        selectedKategori.jar,
                        selectedKategori.jazm,
                    ]
                        .filter(Boolean)
                        .map((val) => ({
                            value: val,
                            label: val,
                        }));

                    tanda.setData(tandaList);

                    let tandaIrob = "";
                    const currentIrob = irob?.data?.[0]?.value || "";

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

                    tanda.setValue(tandaIrob);
                }
            }

            // Simbol
            if (simbol && selectedKedudukan.simbol) {
                simbol.setData([
                    {
                        value: selectedKedudukan.simbol,
                        label: selectedKedudukan.simbol,
                    },
                ]);
                simbol.setValue(selectedKedudukan.simbol);
            }
        });
    }

    bindFormValidation() {
        const form = document.querySelector("form");
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

        instance.setValue("");
        instance.displaySpan.textContent = instance.placeholder;
        instance.wrapper.classList.remove("invalid");
        instance.displaySpan.classList.remove("ar");
    }
}

/* ======================================================
   INITIALIZE
   ====================================================== */

document.addEventListener("DOMContentLoaded", () => {
    new NahwuFormController();
});
