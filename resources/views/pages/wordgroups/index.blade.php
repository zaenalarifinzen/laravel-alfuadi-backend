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
                                {{-- <div class="float-left">
                                    <select class="form-control selectric">
                                        <option>Action For Selected</option>
                                        <option>Move to Draft</option>
                                        <option>Move to Pending</option>
                                        <option>Delete Pemanently</option>
                                    </select>
                                </div> --}}
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
                                    <table class="table-striped table">
                                        <tr>
                                            <th>Id</th>
                                            <th>Surah Id</th>
                                            <th>Verse Number</th>
                                            <th>Order Number</th>
                                            <th>Text</th>
                                        </tr>
                                        @foreach ($wordgroups as $wordgroup)
                                            <tr>
                                                <td>
                                                    {{ $wordgroup->id }}
                                                </td>
                                                <td>
                                                    {{ $wordgroup->surah_number }}
                                                </td>
                                                <td>
                                                    {{ $wordgroup->verse_number }}
                                                </td>
                                                <td>
                                                    {{ $wordgroup->order_number }}
                                                </td>
                                                <td>
                                                    {{ $wordgroup->text }}
                                                </td>
                                            </tr>
                                        @endforeach


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
@endpush
