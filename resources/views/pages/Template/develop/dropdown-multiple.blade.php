@extends('layouts.auth')

@section('title', 'Dropdown Page')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}"> --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Cinzel+Decorative:wght@400;700;900&family=Scheherazade+New:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            background: #f1f1f1 !important;
        }

        .custom-dropdown {
            position: relative;
        }

        .select-btn,
        .options li {
            display: flex;
            cursor: pointer;
            align-items: center;
        }

        .select-btn {
            height: 50px;
            padding: 0 20px;
            border-radius: 7px;
            background: #fff;
            justify-content: space-between;
        }

        .select-btn .ar {
            font-size: 18px;
        }

        .select-btn i {
            transition: transform 0.2s linear;
        }

        .custom-dropdown.active .select-btn i {
            transform: rotate(-180deg);
        }

        .content {
            display: none;
            background: #fff;
            margin-top: 5px;
            padding: 20px;
            border-radius: 7px;
            z-index: 1;
        }

        .custom-dropdown .content {
            width: 100%;
            box-sizing: border-box;
        }

        .custom-dropdown.active .select-btn {
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .custom-dropdown.active .content {
            display: block;
            position: absolute;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .custom-dropdown.disabled .select-btn {
            background: #e9e9e9;
            cursor: not-allowed;
        }

        .content .search {
            position: relative;
        }

        .search i {
            position: absolute;
            left: 10px;
            font-size: 15px;
            color: #999;
            line-height: 40px;
        }

        .search input {
            height: 40px;
            width: 100%;
            outline: none;
            font-size: 15px;
            padding: 0 15px 0 32px;
            border: 1px solid #b7d8d5;
            border-radius: 5px;
        }

        .content .options {
            margin-top: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding-left: 0;
            padding-right: 7px;
        }

        .options::-webkit-scrollbar {
            width: 7px;
        }

        .options::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 25px;
        }

        .options::-webkit-scrollbar-thumb {
            background: #ccc;
            width: 25px;
        }

        .options li {
            height: 40px;
            padding: 0 13px;
            font-size: 18px;
            border-radius: 5px;
        }

        .options li:hover,
        .options li.selected {
            background: #f2f2f2;
        }

        .ar {
            direction: rtl;
            font-family: "Scheherazade New", "Amiri Quran", serif;
            /* font-size: 12px; */
        }
    </style>
@endpush

@section('main')
    <div class="form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="input-kalimat">Kalimat</label>
                <select id="input-kalimat" class="custom-dropdown" name="kalimat"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="input-kategori">Kategori</label>
                <select id="input-kategori" class="custom-dropdown" name="kategori"></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="input-kedudukan">Kedudukan</label>
                <select id="input-kedudukan" class="custom-dropdown" name="kedudukan"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="input-hukum">Hukum</label>
                <select id="input-hukum" class="custom-dropdown" name="hukum"></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="input-irob">Irob</label>
                <select id="input-irob" class="custom-dropdown" name="irob"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="input-tanda">Tanda irob</label>
                <select id="input-tanda" class="custom-dropdown" name="tanda"></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="input-simbol">Simbol</label>
                <select id="input-simbol" class="custom-dropdown" name="simbol"></select>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth/auth-form.js') }}"></script>
    <script>
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
                        return this.raw.kalimat.map(item => ({
                            value: item.id,
                            label_in: item.kalimat_in,
                            label_ar: item.kalimat_ar,
                            label_ar_musyakal: item.kalimat_ar
                        }));

                    case "kategori":
                        return this.raw.kategori.map(item => ({
                            value: item.id,
                            label_in: item.kategori_in,
                            label_ar: item.kategori_ar,
                            label_ar_musyakal: item.kategori_ar_musyakal
                        }));

                    case "kedudukan":
                        return this.raw.kedudukan.map(item => ({
                            value: item.id,
                            label_in: item.kedudukan_in,
                            label_ar: item.kedudukan_ar,
                            label_ar_musyakal: item.kedudukan_ar_musyakal
                        }));

                    case "hukum":
                        // get unique from category
                        return [...new Set(this.raw.kategori.map(k => k.hukum))]
                            .filter(Boolean)
                            .map(hukum => ({
                                value: hukum,
                                label_in: "",
                                label_ar: hukum,
                                label_ar_musyakal: hukum
                            }));

                    case "irob":
                        return [...new Set(this.raw.kedudukan.map(k => k.irob))]
                            .filter(Boolean)
                            .map(irob => ({
                                value: irob,
                                label_in: irob,
                                label_ar: irob,
                                label_ar_musyakal: irob
                            }));

                    case "tanda":
                        const tandaSet = new Set();

                        this.raw.kategori.forEach(k => {
                            [k.rofa, k.nashob, k.jar, k.jazm]
                            .map(val => val ? val.trim() : "")
                                .filter(val => val !== "")
                                .forEach(val => tandaSet.add(val));
                        });

                        return Array.from(tandaSet).map(tanda => ({
                            value: tanda,
                            label_in: tanda,
                            label_ar: tanda,
                            label_ar_musyakal: tanda
                        }));

                    case "simbol":
                        return [...new Set(this.raw.kedudukan.map(k => k.simbol))]
                            .filter(Boolean)
                            .map(simbol => ({
                                value: simbol,
                                label_in: simbol,
                                label_ar: simbol,
                                label_ar_musyakal: simbol
                            }));

                    default:
                        return [];
                }
            }
        }

        class CustomDropdown {

            constructor(selectElement) {
                this.select = selectElement;
                this.dataName = selectElement.name;
                this.placeholder = selectElement.getAttribute("placeholder") ||
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
                `;

                this.select.after(this.wrapper);
            }

            cacheElements() {
                this.selectBtn = this.wrapper.querySelector(".select-btn");
                this.searchInput = this.wrapper.querySelector(".search input");
                this.optionsContainer = this.wrapper.querySelector(".options");
                this.displaySpan = this.selectBtn.querySelector("span");
            }

            async init() {
                await MasterData.load();
                this.data = MasterData.getDataSet(this.dataName);
                console.log(this.dataName, this.data);

                this.populateSelect();
                this.renderOptions();
                this.bindEvents();
                this.setDefaultFromSelect();
            }

            populateSelect() {
                this.select.innerHTML = `<option value="">${this.placeholder}</option>`;

                this.data.forEach(item => {
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

                dataset.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("ar");
                    li.textContent = item.label_ar_musyakal;
                    li.dataset.value = item.value;

                    if (item.id === this.select.value) {
                        li.classList.add("selected");
                    }

                    li.addEventListener("click", () => {
                        if (this.select.disabled) return;
                        this.selectItem(item.id, item.label);
                    });

                    this.optionsContainer.appendChild(li);
                });
            }

            selectItem(value, text) {
                this.select.value = value;
                this.displaySpan.innerHTML = text;
                this.displaySpan.classList.add("ar");

                this.wrapper.classList.remove("active");
                this.renderOptions();
            }

            setDefaultFromSelect() {
                if (!this.select.value) return;

                const selectOption = this.select.options[this.select.selectedIndex];
                this.displaySpan.innerHTML = selectOption.textContent;
            }

            filterOptions() {
                const searched = this.searchInput.value.toLowerCase();

                const filtered = this.data.filter(item => {
                    return item.label_ar_musyakal.includes(searched) ||
                        item.label_ar.includes(searched) ||
                        item.label_in.toLowerCase().includes(searched);
                });

                this.renderOptions(filtered);
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

        // auto initiate all dropdown
        document.querySelectorAll("select.custom-dropdown")
            .forEach(select => new CustomDropdown(select));
    </script>
@endpush
