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

        }

        .content .search {
            position: relative;
        }

        .search i {
            position: absolute;
            left: 15px;
            font-size: 15px;
            color: #999;
            line-height: 40px;
        }

        .search input {
            height: 40px;
            width: 100%;
            outline: none;
            font-size: 15px;
            padding: 0 15px 0 43px;
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
                <select id="input-kalimat" class="custom-dropdown" data-url="/json/data-nahwu.json" name="kalimat"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="input-kategori">Kategori</label>
                <select id="input-kategori" class="custom-dropdown" data-url="/json/data-nahwu.json" name="kategori"></select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="input-hukum">Hukum</label>
                <select id="input-hukum" class="custom-dropdown" data-url="/json/data-nahwu.json" name="hukum"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="input-kedudukan">Kedudukan</label>
                <select id="input-kedudukan" class="custom-dropdown" data-url="/json/data-nahwu.json" name="kedudukan"></select>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth/auth-form.js') }}"></script>
    <script>
        class CustomDropdown {
            constructor(selectElement) {
                this.select = selectElement;
                this.dataUrl = selectElement.dataset.url;
                this.placeholder = selectElement.getAttribute("placeholder") ||
                    `Pilih ${selectElement.getAttribute("name")}`;

                this.data = [];

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
                await this.loadData();
                this.populateSelect();
                this.renderOptions();
                this.bindEvents();
                this.setDefaultFromSelect();
            }

            async loadData() {
                if (!this.dataUrl) return;

                try {
                    const response = await fetch(this.dataUrl);
                    const json = await response.json();
                    this.data = json.kedudukan || [];
                } catch (error) {
                    console.error("Error loading data: ", error);
                }
            }

            populateSelect() {
                this.select.innerHTML = `<option value="">${this.placeholder}</option>`;

                this.data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.kedudukan_ar_musyakal;
                    this.select.appendChild(option);
                });
            }

            renderOptions(filteredData = null) {
                const dataset = filteredData || this.data;
                this.optionsContainer.innerHTML = "";

                if (!dataset || dataset.length === 0) {
                    this.optionsContainer.innerHTML = "<span>Data tidak ditemukan</span>";
                    return;
                }

                dataset.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("ar");
                    li.textContent = item.kedudukan_ar_musyakal;
                    li.dataset.value = item.id;

                    if (item.id === this.select.value) {
                        li.classList.add("selected");
                    }

                    li.addEventListener("click", () => {
                        if (this.select.disabled) return;
                        this.selectItem(item.id, item.kedudukan_ar_musyakal);
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

                const selectOption = this.select.option[this.select.selectedIndex];
                this.displaySpan.innerHTML = selectOption.textContent;
            }

            filterOptions() {
                const searched = this.searchInput.value.toLowerCase();

                const filtered = this.data.filter(item => {
                    return item.kedudukan_ar_musyakal.includes(searched) ||
                        item.kedudukan_ar.includes(searched) ||
                        item.kedudukan_in.toLowerCase().includes(searched);
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
