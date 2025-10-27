@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Input I'rob</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Grup Kalimah</h4>
                    </div>
                    <div class="card-body">
                        <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                            {{-- <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators2" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators2" data-slide-to="2"></li>
                            </ol> --}}
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="d-block w-100 h-50">
                                        <h4 class="arabic-text" style="text-align: center">نَصَرْتُ</h4>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="d-block w-100">
                                        <h4 class="arabic-text" style="text-align: center">زَيْدً</h4>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="false"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="false"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Data Kalimah</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-striped table" id="sortable-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <i class="fa-solid fa-sort"></i>
                                        </th>
                                        <th>Lafadz</th>
                                        <th>Terjemah</th>
                                        <th>Kalimah</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="sort-handler">
                                                <i class="fa-solid fa-grip"></i>
                                            </div>
                                        </td>
                                        <td class="arabic-text">نَصَرَ</td>
                                        <td>Menolong</td>
                                        <td>Fiil</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">Edit</a>
                                            <a href="#" class="btn btn-secondary">Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="sort-handler">
                                                <i class="fa-solid fa-grip"></i>
                                            </div>
                                        </td>
                                        <td class="arabic-text">تُ</td>
                                        <td>Zaid</td>
                                        <td>Isim</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">Edit</a>
                                            <a href="#" class="btn btn-secondary">Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="sort-handler">
                                                <i class="fa-solid fa-grip"></i>
                                            </div>
                                        </td>
                                        <td class="arabic-text">عَمْرً</td>
                                        <td>Amr</td>
                                        <td>Isim</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">Edit</a>
                                            <a href="#" class="btn btn-secondary">Detail</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
