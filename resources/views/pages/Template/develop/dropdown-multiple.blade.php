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
            margin: 130px auto 0;
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
            font-size: 18px;
            justify-content: space-between;
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
    <div class="wrapper">
        <div class="custom-dropdown" data-url="{{ asset('json/data-nahwu.json') }}">
            <div class="select-btn">
                <span>Pilih 1</span>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div class="content">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari">
                </div>
                <ul class="options"></ul>
            </div>
        </div>
        <div class="custom-dropdown" data-url="{{ asset('json/data-nahwu.json') }}">
            <div class="select-btn">
                <span>Pilih 2</span>
                <i class="fa-solid fa-angle-down"></i>
            </div>
            <div class="content">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Cari">
                </div>
                <ul class="options"></ul>
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
            constructor(wrapper) {
                this.wrapper = wrapper;
                this.selectBtn = wrapper.querySelector(".select-btn");
                this.searchInput = wrapper.querySelector("input");
                this.optionsContainer = wrapper.querySelector(".options");
                this.dataUrl = wrapper.dataset.url;
                this.data = [];

                this.init();
            }

            async init() {
                await this.loadData();
                this.renderOptions();
                this.bindEvents();
            }

            async loadData() {
                try {
                    const response = await fetch(this.dataUrl);
                    const json = await response.json();
                    this.data = json.kedudukan || [];
                } catch (error) {
                    console.error("Error loading data: ", error);
                }
            }

            renderOptions(selectedItem = null) {
                this.optionsContainer.innerHTML = "";

                this.data.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("ar");
                    li.textContent = item.kedudukan_ar_musyakal;

                    if (item.kedudukan_ar_musyakal === selectedItem) {
                        li.classList.add("selected");
                    }

                    li.addEventListener("click", () => {
                        this.selectItem(li);
                    });

                    this.optionsContainer.appendChild(li);
                });
            }

            selectItem(li) {
                this.searchInput.value = "";
                this.renderOptions(li.textContent);
                this.wrapper.classList.remove("active");
                this.selectBtn.querySelector("span").innerText = li.textContent;
                this.selectBtn.querySelector("span").classList.add("ar");
            }

            filterOptions() {
                const searched = this.searchInput.value.toLowerCase();

                const filtered = this.data.filter(item => {
                    return item.kedudukan_ar_musyakal.includes(searched) ||
                        item.kedudukan_ar.includes(searched) ||
                        item.kedudukan_in.toLowerCase().includes(searched);
                });

                this.optionsContainer.innerHTML = "";

                if (filtered.length === 0) {
                    this.optionsContainer.innerHTML = `<li">Data tidak ditemukan</li>`;
                    return;
                }

                filtered.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("ar");
                    li.textContent = item.kedudukan_ar_musyakal;

                    li.addEventListener("click", () => {
                        this.selectItem(li);
                    });

                    this.optionsContainer.appendChild(li);
                });
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
        document.querySelectorAll(".custom-dropdown").forEach(dropdown => {
            new CustomDropdown(dropdown);
        });
    </script>
@endpush
