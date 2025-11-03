@extends('layouts.app')

@section('title', 'Kalimat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Surah</h1>
                {{-- <div class="section-header-button">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add New</a>
                </div> --}}
                {{-- <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Words</div>
                </div> --}}
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Kalimah</h2>
                <p class="section-lead">
                    Daftar kalimah yang ada dalam Al-Quran.
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Semua kalimah</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-right">
                                    <form method="GET" action="{{ route('words.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="name">
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
                                                <th>ID Kalimat</th>
                                                <th>ID Grup</th>
                                                <th>Teks</th>
                                                <th>Terjemah</th>
                                                <th>Kedudukan</th>
                                            </tr>
                                        </thead>
                                        @foreach ($words as $word)
                                            <tr>
                                                <td>
                                                    {{ $word->id }}
                                                </td>
                                                <td>
                                                    {{ $word->word_group_id }}
                                                </td>
                                                <td class="arabic-text">
                                                    {{ $word->text }}
                                                </td>
                                                <td>
                                                    {{ $word->translation }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $word->kedudukan }}
                                                </td>

                                            </tr>
                                        @endforeach


                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $words->withQueryString()->links() }}
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
@endpush
