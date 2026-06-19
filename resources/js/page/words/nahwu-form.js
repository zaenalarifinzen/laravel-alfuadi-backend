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

    bindRelations() {
        const kalimat = this.instances.kalimat;

        if (!kalimat) return;

        kalimat.select.addEventListener("change", (e) => {
            const isRestoring = e.detail?.isRestoring || false;

            if (!isRestoring) {
                this.resetDropdown(this.instances.kategori);
                this.resetDropdown(this.instances.kedudukan);
                this.resetDropdown(this.instances.hukum);
                this.resetDropdown(this.instances.irob);
                this.resetDropdown(this.instances.tanda);
                this.resetDropdown(this.instances.simbol);
            }

            const selected = kalimat.select.value;

            this.updateKategoriOptions(selected);
            this.updateKedudukanOptions(selected);

            this.enableAllRelationFields();
            this.applyKalimatFieldRules(selected);
        });
    }

    updateKategoriOptions(selectedKalimat) {
        const kategori = this.instances.kategori;
        if (!kategori) return;

        const data = MasterData.raw;
        const filteredKategori = data.kategori
            .filter((k) => k.id_kalimat === selectedKalimat)
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

        this.autoFillHukumAndSimbolByKategori(selectedKategori);
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

    autoFillHukumAndSimbolByKategori(selectedKategori) {
        if (!this.instances.hukum || !selectedKategori.hukum) return;

        this.instances.hukum.setData([
            this.createOptionItem(selectedKategori.hukum, selectedKategori.hukum),
        ]);
        this.instances.hukum.setValue(selectedKategori.hukum, true);

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
