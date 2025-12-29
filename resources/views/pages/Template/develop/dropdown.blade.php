@extends('layouts.auth')

@section('title', 'Dropdown Page')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}"> --}}

    <style>
        body {
            background: #a3c7c4 !important;
        }

        .wrapper {
            width: 370px;
            margin: 130px auto 0;
        }

        .select-btn,
        .options li {
            display: flex;
            cursor: pointer;
            align-items: center;
        }

        .select-btn {
            height: 65px;
            font-size: 22px;
            padding: 0 20px;
            border-radius: 7px;
            background: #fff;
            justify-content: space-between;
        }

        .select-btn i {
            font-size: 31px;
            transition: transform 0.2s linear;
        }

        .wrapper.active .select-btn i {
            transform: rotate(-180deg);
        }

        .content {
            display: none;
            background: #fff;
            margin-top: 5px;
            padding: 20px;
            border-radius: 7px;
        }

        .wrapper.active .content {
            display: block;
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
            max-height: 250px;
            overflow-y: auto;
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
            font-size: 15px;
            border-radius: 5px;
        }

        .options li:hover {
            background: #f2f2f2;
        }
    </style>
@endpush

@section('main')
    <div class="wrapper">
        <div class="select-btn">
            <span>Pilih provinsi</span>
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
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth/auth-form.js') }}"></script>
    <script>
        const wrapper = document.querySelector(".wrapper");
        const selectBtn = wrapper.querySelector(".select-btn");
        searchInput = wrapper.querySelector("input");
        options = wrapper.querySelector(".options");

        let provinces = ["Jawa Timur", "Jawa Tengah", "Jawa Barat", "Yogyakarta", "DKI Jakarta", "Palembang", "Aceh",
            "Samarinda", "Pontianak", "Jayapura"
        ];

        function addProvinces(selectedProvince) {
            options.innerHTML = "";
            provinces.forEach(province => {
                let isSelected = province== selectedProvince ? "selected" : "";
                let li = `<li onClick="updateName(this)" class="${isSelected}">${province}</li>`;
                options.insertAdjacentHTML("beforeend", li);
            });
        }
        addProvinces()

        function updateName(selectedLi) {
            searchInput.value = "";
            addProvinces(selectedLi.innerText)
            wrapper.classList.remove("active");
            selectBtn.firstElementChild.innerText = selectedLi.innerText;
        }

        searchInput.addEventListener("keyup", () => {
            let filtered = [];
            let searchedValue = searchInput.value.toLowerCase();
            filtered = provinces.filter(data => {
                return data.toLowerCase().startsWith(searchedValue);
            }).map(data => `<li onClick="updateName(this)">${data}</li>`).join("");
            options.innerHTML = filtered ? filtered :  `<li">Data tidak ditemukan</li>`;
                   
        })

        selectBtn.addEventListener("click", () => {
            wrapper.classList.toggle("active");
        })
    </script>
@endpush
