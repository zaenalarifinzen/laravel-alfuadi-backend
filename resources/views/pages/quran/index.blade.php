@extends('layouts.quran')

@section('title', 'Al-Quran')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Al-Qur'an</h1>
            </div>
            <div class="section-body">

                {{-- <div class="card">
                    <div class="card-header">
                        <form class="card-header-form">
                            <input type="text" name="search" class="form-control" placeholder="Search">
                        </form>
                    </div>
                </div> --}}


                <div class="row gx-3 gy-2">
                    @foreach ($surahs as $surah)
                        <div class="col-12 col-md-6 col-lg-4 h">
                            <div class="card mb-2"
                                onclick="window.location.href='{{ route('quran.surah', ['id' => $surah->id]) }}'"
                                style="cursor:pointer">
                                <div class="card-body p-3">
                                    <div class="row align-items-center g-2">
                                        <div class="col-2 text-center">
                                            <figure class="avatar mr-2 avatar-md bg-primary text-white"
                                                data-initial="{{ $surah->id }}"></figure>
                                        </div>

                                        <div class="col-6">
                                            <h6 class="text-primary mb-1">{{ $surah->name }}</h6>
                                            <small class="text-muted">{{ $surah->name_id }}</small>
                                        </div>

                                        <div class="col-4 text-end">
                                            <span class="badge bg-light text-dark">{{ $surah->verse_count }} ayat</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
