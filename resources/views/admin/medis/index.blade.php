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

        .form-control {
            width: auto;
            /* Input tanggal akan mempertahankan lebar berdasarkan kontennya */
            flex-grow: 2;
            /* Input tanggal akan tumbuh lebih banyak dari tombol */
        }


        .bullet {
            font-weight: bold;
            font-size: 1.1em;
            /* Membuat teks lebih tebal */
        }
    </style>
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Rekam Medis</li>
        </ol>
    </nav>
    <div class="flash-message" data-flash-message="@if (session()->has('success')) {{ session('success') }} @endif">
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
                    <div class="justify-content-start d-none d-md-block">
                        <form action="{{ route('medis.filters') }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center">
                                <a href="/admin/medis/create" class="btn btn-xs btn-dark fw-bold me-3 p-2">
                                    <i class='bx bx-add-to-queue'></i> Rekam Medis
                                </a>
                                <form action="/admin/medis" method="post">
                                    <input class="form-control" name="startDate" type="date" />
                                    <div class="me-2 ms-2">-</div>
                                    <input class="form-control" name="endDate" type="date" />
                                    <button type="submit" class="btn btn-xs btn-dark fw-bold ms-2 p-2"><i
                                            class='bx bx-filter'></i>&nbsp;Filter</button>
                                </form>
                        </form>
                        <form action="{{ route('medis.download_pdf') }}" method="GET">
                            <input type="hidden" name="startDate" value="{{ request('startDate') }}">
                            <input type="hidden" name="endDate" value="{{ request('endDate') }}">
                            <button type="submit" class="btn btn-xs btn-dark fw-bold ms-2 p-2"><i
                                    class='bx bxs-file-pdf'></i>&nbsp; Download PDF</button>
                        </form>
                    </div>

                </div>
                <div class="justify-content-end">
                    <!-- Search -->
                    <form action="/admin/medis" method="GET">
                        <div class="input-group">
                            <input type="search" class="form-control" name="search" id="search"
                                style="border: 1px solid #d9dee3;" value="{{ request('search') }}"
                                placeholder="Cari rekam medis..." autocomplete="off" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <ul class="p-0 m-0">
                    <div class="table-responsive text-nowrap" style="border-radius: 3px;">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">No. RM</th>
                                    <th class="text-white">Nama Lengkap</th>
                                    <th class="text-white">Tanggal Periksa</th>
                                    <th class="text-white">Keluhan Utama</th>
                                    <th class="text-white">Terapi</th>
                                    <th class="text-white text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($rekamMedisList as $index => $medis)
                                    <!-- Tampilkan informasi rekam medis -->
                                    <tr>
                                        <!-- Kita asumsikan kolom pasien_id menyimpan ID atau nama pasien -->
                                        <td>{{ $loop->iteration }} </td>
                                        <td><span
                                                class="badge bg-label-secondary fw-bold">{{ $medis->pasien->no_rm }}</span>
                                        </td>
                                        <td>{{ $medis->pasien->name }}</td>
                                        <td class="text-justify">
                                            {{ Carbon\Carbon::parse($medis->created_at)->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}WIB

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
                                @if ($rekamMedisList->isEmpty())
                                    <tr>
                                        <td colspan="100" class="text-center">Tidak ada data Rekam Medis!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </ul>
                @if (!$rekamMedisList->isEmpty())
                    <div class="mt-3 pagination-mobile">
                        {{ $rekamMedisList->withQueryString()->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>




    @include('sweetalert::alert')
@endsection
