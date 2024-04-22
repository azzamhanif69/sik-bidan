@extends('layouts.main.index')
@section('container')
    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        @media screen and (min-width: 1320px) {
            #search {
                width: 250px;
            }
        }

        @media screen and (max-width: 575px) {
            .pagination-mobile {
                display: flex;
                justify-content: end;
            }
        }

        .required-label::after {
            content: " *";
            color: red;
        }

        .record-card {
            max-width: 500px;
            margin: auto;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
            border: none;
        }

        .record-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: .25rem .25rem 0 0;
            /* Rounded corners at the top */
        }

        .record-body {
            padding: 15px;
            background-color: white;
        }

        .record-item {
            display: flex;
            align-items: center;
            /* This will vertically center align the label and the value */
            justify-content: space-between;
            padding-bottom: 0.5rem;
        }

        .record-item:last-child {
            padding-bottom: 0;
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/pasien">Data Pasien</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rekam Medis</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 col-lg-12 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
                    <div class="justify-content-start d-none d-md-block">
                        <form action="/admin/pasien" method="GET">
                            <div>
                                <a href="/admin/pasien" class="btn btn-outline-danger back-button">
                                    <i class='bx bx-left-arrow-alt'></i>&nbsp;Kembali
                                </a>
                                <div class="card-body record-body">
                                    <div class="record-item">
                                        <strong>Nomor Rekam Medis</strong>
                                        <span class="badge bg-label-secondary fw-bold">{{ $pasien->no_rm }}</span>
                                    </div>
                                    <div class="record-item">
                                        <strong>Nama Lengkap</strong>
                                        <span class="badge bg-label-secondary fw-bold">{{ $pasien->name }}</span>
                                    </div>
                                    <div class="record-item">
                                        <strong>Usia</strong>
                                        <span
                                            class="badge bg-label-secondary fw-bold">{{ $pasien->date_of_birth . ' ' . 'Tahun' }}</span>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    <div class="table-responsive text-nowrap" style="border-radius: 3px;">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">No.RM</th>
                                    <th class="text-white">NAMA LENGKAP</th>
                                    <th class="text-white">TANGGAL PERIKSA</th>
                                    <th class="text-white">KELUHAN UTAMA</th>
                                    <th class="text-white">TERAPI</th>
                                    <th class="text-white text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($rekamMedis as $medis)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td><span
                                                class="badge bg-label-secondary fw-bold">{{ $medis->pasien->no_rm }}</span>
                                        </td>
                                        <td>{{ $medis->pasien->name }}</td>
                                        <td class="text-justify">
                                            {{ Carbon\Carbon::parse($medis->created_at)->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}
                                            WIB
                                        </td>
                                        <td>{{ $medis->keluhan }}</td>
                                        <td>
                                            @foreach ($medis->reseps as $resep)
                                                <span class="bullet">&bull;</span> {{ $resep->obat->nama_obat }}
                                                {{ $resep->obat->sediaan }}
                                                {{ $resep->obat->dosis }}{{ $resep->obat->satuan }},
                                                {{ $resep->jumlah }} pcs : {{ $resep->aturan }}
                                                @if (!$loop->last)
                                                    <br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="/admin/medis/{{ $medis->id }}/edit" type="button"
                                                class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip"
                                                data-popup="tooltip-custom" data-bs-placement="auto" title="Edit Medis">
                                                <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($rekamMedis->isEmpty())
                                    <tr>
                                        <td colspan="100" class="text-center">Tidak ada data Rekam Medis!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </ul>
                @if (!$rekamMedis->isEmpty())
                    <div class="mt-3 pagination-mobile">
                        {{ $rekamMedis->withQueryString()->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
    @include('sweetalert::alert')
@endsection
