const MasterData = {
    raw: null,

    async load() {
        if (this.raw) return this.raw;
        const res = await fetch("/json/data-nahwu.json");
        this.raw = await res.json();
        return this.raw;
    },

    getDataSet(name) {
        if (!this.raw) return [];

        switch (name) {
            case "kalimat":
                return this.raw.kalimat.map((item) => ({
                    value: item.id,
                    label_in: item.kalimat_in,
                    label_ar: item.kalimat_ar,
                    label_ar_musyakal: item.kalimat_ar,
                }));

            case "kategori":
                return this.raw.kategori.map((item) => ({
                    value: item.id,
                    label_in: item.kategori_in,
                    label_ar: item.kategori_ar,
                    label_ar_musyakal: item.kategori_ar_musyakal,
                }));

            case "kedudukan":
                return this.raw.kedudukan.map((item) => ({
                    value: item.id,
                    label_in: item.kedudukan_in,
                    label_ar: item.kedudukan_ar,
                    label_ar_musyakal: item.kedudukan_ar_musyakal,
                }));

            case "hukum":
                // get unique from category
                return [...new Set(this.raw.kategori.map((k) => k.hukum))]
                    .filter(Boolean)
                    .map((hukum) => ({
                        value: hukum,
                        label_in: "",
                        label_ar: hukum,
                        label_ar_musyakal: hukum,
                    }));

            case "irob":
                return [...new Set(this.raw.kedudukan.map((k) => k.irob))]
                    .filter(Boolean)
                    .map((irob) => ({
                        value: irob,
                        label_in: irob,
                        label_ar: irob,
                        label_ar_musyakal: irob,
                    }));

            case "tanda":
                const tandaSet = new Set();

                this.raw.kategori.forEach((k) => {
                    [k.rofa, k.nashob, k.jar, k.jazm]
                        .map((val) => (val ? val.trim() : ""))
                        .filter((val) => val !== "")
                        .forEach((val) => tandaSet.add(val));
                });

                return Array.from(tandaSet).map((tanda) => ({
                    value: tanda,
                    label_in: tanda,
                    label_ar: tanda,
                    label_ar_musyakal: tanda,
                }));

            case "simbol":
                return [...new Set(this.raw.kedudukan.map((k) => k.simbol))]
                    .filter(Boolean)
                    .map((simbol) => ({
                        value: simbol,
                        label_in: simbol,
                        label_ar: simbol,
                        label_ar_musyakal: simbol,
                    }));

            default:
                return [];
        }
    },
};

class CustomDropdown {
    constructor(selectElement) {
        this.select = selectElement;
        this.isRequired = this.select.hasAttribute("required");
        this.select.removeAttribute("required");
        this.dataName = selectElement.name;
        this.placeholder =
            selectElement.getAttribute("placeholder") ||
            `Pilih ${selectElement.getAttribute("name")}`;

        this.buildHTML();
        this.cacheElements();
        this.init();
    }

    buildHTML() {
        // hide original select
        this.select.style.display = "none";

        this.wrapper = document.createElement("div");
        this.wrapper.classList.add("custom-dropdown");

        if (this.select.disabled) {
            this.wrapper.classList.add("disabled");
        }

        // if disabled, remove icon dropdown and set placeholder to blank
        if (this.select.disabled) {
            this.wrapper.innerHTML = `
                        <div class="select-btn">
                            <span></span>
                        </div>
                    `;
            this.select.after(this.wrapper);
            return;
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
        this.data = MasterData.getDataSet(this.dataName);
        this.populateSelect();
        this.renderOptions();
        this.bindEvents();
        this.setDefaultFromSelect();
    }

    populateSelect() {
        this.select.innerHTML = `<option value="">${this.placeholder}</option>`;

        this.data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.value;
            option.textContent = item.label_ar_musyakal;
            this.select.appendChild(option);
        });
    }

    renderOptions(filteredData = null) {
        const dataset = filteredData || this.data;
        this.optionsContainer.innerHTML = "";

        if (!dataset || dataset.length === 0) {
            this.optionsContainer.innerHTML = `<span>Data tidak ditemukan</span>`;
            return;
        }

        dataset.forEach((item) => {
            const li = document.createElement("li");
            li.classList.add("ar");
            li.textContent = item.label_ar_musyakal;
            li.dataset.value = item.value;

            if (item.id === this.select.value) {
                li.classList.add("selected");
            }

            li.addEventListener("click", () => {
                if (this.select.disabled) return;
                this.selectItem(item.value, item.label_ar_musyakal);
            });

            this.optionsContainer.appendChild(li);
        });
    }

    selectItem(value, text) {
        this.select.value = value;
        this.displaySpan.innerHTML = text;
        this.displaySpan.classList.add("ar");
        this.wrapper.classList.remove("active");
        this.validate();
        this.renderOptions();
    }

    setDefaultFromSelect() {
        if (!this.select.value) return;

        const selectOption = this.select.options[this.select.selectedIndex];
        this.displaySpan.innerHTML = selectOption.textContent;
    }

    filterOptions() {
        const searched = this.searchInput.value.toLowerCase();

        const filtered = this.data.filter((item) => {
            return (
                item.label_ar_musyakal.includes(searched) ||
                item.label_ar.includes(searched) ||
                item.label_in.toLowerCase().includes(searched)
            );
        });

        this.renderOptions(filtered);
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
            this.wrapper.classList.toggle("active");
        });

        this.searchInput.addEventListener("keyup", () => {
            this.filterOptions();
        });

        window.addEventListener("click", (event) => {
            if (!this.wrapper.contains(event.target)) {
                this.searchInput.value = "";
                this.renderOptions();
                this.wrapper.classList.remove("active");
            }
        });
    }
}

document.querySelector("form").addEventListener("submit", function (e) {
    let valid = true;

    document.querySelectorAll("select.custom-dropdown").forEach((select) => {
        const instance = select._customInstance;
        if (instance && !instance.validate()) {
            valid = false;
        }
    });

    if (!valid) e.preventDefault();
});

// auto initiate all dropdown
document.querySelectorAll("select.custom-dropdown").forEach((select) => {
    const instance = new CustomDropdown(select);
    select._customInstance = instance;
});
