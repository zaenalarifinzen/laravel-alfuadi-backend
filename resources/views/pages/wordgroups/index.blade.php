@extends('layouts.app')

@section('title', 'Word Groups')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Word Groups</h1>
                {{-- <div class="section-header-button">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add New</a>
                </div> --}}
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Word Groups</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Word Groups</h2>
                <p class="section-lead">
                    You can manage all Word Groups, such as editing, deleting and more.
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Verse</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-left">
                                    {{-- <select class="form-control selectric">
                                        <option>Group</option>
                                        <option>Delete</option>
                                    </select> --}}
                                    {{-- <div class="mb-3">
                                        <form id="merge-form" action="{{ route('word_groups.merge') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="ids" id="selected-ids">
                                            <button type="submit" class="btn btn-primary btn-lg disabled"
                                                id="btn-merge">Merge</button>
                                        </form>
                                    </div> --}}
                                </div>
                                <div class="float-right">
                                    <form method="GET" action="{{ route('wordgroups.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="surah_id">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-hover table">
                                        <thead>
                                            <tr>
                                                {{-- <th class="pt-2 text-center">
                                                    <div class="custom-checkbox custom-checkbox-table custom-control">
                                                        <input type="checkbox" data-checkboxes="mygroup"
                                                            data-checkbox-role="dad" class="custom-control-input"
                                                            id="checkbox-all">
                                                        <label for="checkbox-all"
                                                            class="custom-control-label">&nbsp;</label>
                                                    </div>
                                                </th> --}}
                                                <th>Id</th>
                                                <th>Surah Id</th>
                                                <th>Verse Number</th>
                                                <th>Verse Id</th>
                                                <th>Order Number</th>
                                                <th>Text Uthmani</th>
                                                <th>Text Indopak</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($wordgroups as $wordgroup)
                                                <tr>

                                                    {{-- <td class="text-center">
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                class="custom-control-input row-checkbox"
                                                                id="checkbox-{{ $wordgroup->id }}"
                                                                value="{{ $wordgroup->id }}">
                                                            <label for="checkbox-{{ $wordgroup->id }}"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </td> --}}
                                                    <td>{{ $wordgroup->id }}</td>
                                                    <td>{{ $wordgroup->surah_id }}</td>
                                                    <td>{{ $wordgroup->verse_number }}</td>
                                                    <td>{{ $wordgroup->verse_id }}</td>
                                                    <td>{{ $wordgroup->order_number }}</td>
                                                    <td dir="rtl" style="font-family: 'Scheherazade New', serif;">
                                                        {{ $wordgroup->text }}
                                                    </td>
                                                    <td dir="rtl" style="font-family: 'Scheherazade New', serif;">
                                                        {{ $wordgroup->text_indopak }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="float-right">
                                    {{ $wordgroups->withQueryString()->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script src="{{ asset('js/page/modules-toastr.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mergeButton = document.getElementById('btn-merge');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const idsInput = document.getElementById('selected-ids');
            const mergeForm = document.getElementById('merge-form');

            function updateMergeButton() {
                const checkedCount = Array.from(checkboxes).filter(x => x.checked).length;

                if (checkedCount >= 2) {
                    mergeButton.classList.remove('disabled');
                } else {
                    mergeButton.classList.add('disabled');
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateMergeButton);
            });

            mergeForm.addEventListener('submit', (e) => {
                const selectedIds = Array.from(checkboxes)
                    .filter(x => x.checked)
                    .map(x => x.value);

                if (mergeButton.classList.contains('disabled')) {
                    e.preventDefault();
                    return;
                }

                if (selectedIds.length < 2) {
                    e.preventDefault();
                    alert('Pilih minimal 2 baris untuk merge');
                    return
                }

                if (!confirm('Yakin ingin merge baris ini?')) {
                    e.preventDefault();
                    return
                }

                idsInput.value = selectedIds.join(',');
            });
        });
    </script>
@endpush
