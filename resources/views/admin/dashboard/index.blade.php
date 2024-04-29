@extends('layouts.main.index')
@section('container')
    <style>
        .apexcharts-legend-series {
            display: none;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .apexcharts-title-text {
            font-size: 1rem;
            font-weight: 700 !important;
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
    <div class="flash-message" data-flash-message="@if (session()->has('success')) {{ session('success') }} @endif">
    </div>
    <div class="row">
        <div class="col-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="row p-2 p-lg-0">
                        <div class="col-md-4">
                            <div class="stats-icon" style="background-color: #001e80;">
                                <i class="bx bx-group text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted mt-3 mt-lg-0 fw-bold mb-2">Total Pasien</h6>
                            <h6 class="mb-0 fw-bold">{{ $totalPatient }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="row p-2 p-lg-0">
                        <div class="col-md-4">
                            <div class="stats-icon" style="background-color: #006d80;">
                                <i class='bx bx-sun fs-3' style='color:#ffffff'></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted mt-3 mt-lg-0 fw-bold mb-2">Kunjungan Hari ini</h6>
                            <h6 class="mb-0 fw-bold">{{ $todayVisits }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="row p-2 p-lg-0">
                        <div class="col-md-4">
                            <div class="stats-icon" style="background-color: #48f702;">
                                <i class='bx bx-male-sign fs-3' style='color:#ffffff'></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted mt-3 mt-lg-0 fw-bold mb-2">Pasien Laki-laki</h6>
                            <h6 class="mb-0 fw-bold">{{ $totalMalePatients }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="row p-2 p-lg-0">
                        <div class="col-md-4">
                            <div class="stats-icon" style="background-color: #ff57d0;">
                                <i class='bx bx-female-sign fs-3' style='color:#ffffff'></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted mt-3 mt-lg-0 fw-bold mb-2">Pasien Perempuan</h6>
                            <h6 class="mb-0 fw-bold">{{ $totalFemalePatients }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Stok Obat -->
        <div class="col-lg-6 mt-4" id="obatChart">
            <div class="card">
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <div class="table-responsive"> <!-- Menambahkan table-responsive untuk responsivitas -->
                            {{-- <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Nama Obat</th>
                                        <th class="text-white text-center">Stok Terpakai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topObats as $index => $obat)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $obat->nama_obat . ' ' . $obat->sediaan . ' ' . $obat->dosis . '' . $obat->satuan }}
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-center bg-dark rounded-pill">{{ $obat->usage_count }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                            {!! $obatChart->container() !!}
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-4" id="keluhanChart">
            <div class="card">
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <div class="table-responsive">
                            {!! $keluhanChart->container() !!}
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mt-4" id="usiaChart">
            <div class="card">
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <div class="table-responsive">
                            {!! $usiaChart->container() !!}
                        </div>
                    </ul>
                </div>
            </div>
        </div>


    </div>
    <script src="{{ $obatChart->cdn() }}"></script>
    <script src="{{ $keluhanChart->cdn() }}"></script>
    <script src="{{ $usiaChart->cdn() }}"></script>

    {!! $obatChart->script() !!}
    {!! $keluhanChart->script() !!}
    {!! $usiaChart->script() !!}
    @include('sweetalert::alert')
@endsection
