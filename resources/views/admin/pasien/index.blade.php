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

        .table-responsive {
            overflow-x: auto;
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
    </style>

    <div class="flash-message" data-flash-message="@if (session()->has('success')) {{ session('success') }} @endif">
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Data Pasien</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 col-lg-12 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
                    <div class="justify-content-start d-none d-md-block">
                        <form action="{{ route('pasien.filters') }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center">
                                <a href="/admin/pasien/create" class="btn btn-xs btn-dark fw-bold me-3 p-2">
                                    <i class='bx bx-add-to-queue'></i> Pasien Baru
                                </a>
                                <input class="form-control" name="startDate" type="date"
                                    value="{{ request('startDate') }}" />
                                <div class="me-2 ms-2">-</div>
                                <input class="form-control" name="endDate" type="date"
                                    value="{{ request('endDate') }}" />
                                <button type="submit" class="btn btn-xs btn-dark fw-bold ms-2 p-2"><i
                                        class='bx bx-filter'></i>&nbsp;Filter</button>
                        </form>
                        <form action="{{ route('pasien.download_pdf') }}" method="GET">
                            <input type="hidden" name="startDate" value="{{ request('startDate') }}">
                            <input type="hidden" name="endDate" value="{{ request('endDate') }}">
                            <button type="submit" class="btn btn-xs btn-dark fw-bold ms-2 p-2"><i
                                    class='bx bxs-file-pdf'></i>&nbsp; Download PDF</button>
                        </form>
                    </div>
                </div>
                <div class="justify-content-end">
                    <!-- Search -->
                    <form action="/admin/pasien" method="GET">
                        <div class="input-group">
                            <input type="search" class="form-control" name="search" id="search"
                                style="border: 1px solid #d9dee3;" value="{{ request('search') }}"
                                placeholder="Cari data pasien..." autocomplete="off" />
                        </div>
                    </form>
                    {{-- <input type="text" id="search" placeholder="Search...">
                        <ul id="search-results"></ul> --}}
                    <!-- /Search -->
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
                                    <th class="text-white">Alamat</th>
                                    <th class="text-white">Tanggal Lahir</th>
                                    <th class="text-white">Ditambahkan Pada</th>
                                    <th class="text-white text-center">Umur</th>
                                    <th class="text-white">Jenis Kelamin</th>
                                    <th class="text-white">Nomor Telepon</th>
                                    <th class="text-white text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($pasiens as $index => $pasien)
                                    <tr>
                                        <td>{{ $index + 1 }} </td>
                                        <td><span class="badge bg-label-secondary fw-bold">{{ $pasien->no_rm }}</span>
                                        </td>
                                        <td>
                                            {{ $pasien->name }}
                                        </td>
                                        <td>{{ $pasien->address }}</td>
                                        <td>
                                            {{ Carbon\Carbon::parse($pasien->birth)->locale('id')->isoFormat('D MMMM YYYY') }}
                                        </td>
                                        <td>{{ $pasien->created_at->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}
                                        </td>
                                        <td class="text-center"><span
                                                class="badge badge-center bg-dark rounded-pill">{{ $pasien->date_of_birth }}</span>
                                        </td>
                                        <td>
                                            @if ($pasien->gender == 'Laki-laki')
                                            <span class="badge bg-label-primary fw-bold">Laki-Laki</span>@else<span
                                                    class="badge fw-bold"
                                                    style="color: #ff6384 !important; background-color: #ffe5eb !important;">Perempuan</span>
                                            @endif
                                        </td>
                                        <td>{{ $pasien->phone }}</td>
                                        <td class="text-center">
                                            <a href="/admin/pasien/{{ $pasien->id }}" type="button"
                                                class="btn btn-icon btn-secondary btn-sm" data-bs-toggle="tooltip"
                                                data-popup="tooltip-custom" data-bs-placement="auto" title="Rekam Medis"
                                                data-code="{{ encrypt($pasien->id) }}" data-name="{{ $pasien->name }}">
                                                <span class="tf-icons bx bx-book-alt" style="font-size: 15px;"></span>
                                            </a>
                                            <a href="/admin/pasien/{{ $pasien->id }}/edit" type="button"
                                                class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip"
                                                data-popup="tooltip-custom" data-bs-placement="auto" title="Ubah Pasien"
                                                data-code="{{ encrypt($pasien->id) }}" data-name="{{ $pasien->name }}">
                                                <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
                                            </a>
                                            <button type="button"
                                                class="btn btn-icon btn-danger btn-sm buttonDeletePatient"
                                                data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                data-bs-placement="auto" title="Hapus Pasien"
                                                data-code="{{ encrypt($pasien->id) }}" data-name="{{ $pasien->name }}"
                                                id="buttonDeletePatient">
                                                <span class="tf-icons bx bx-trash" style="font-size: 14px;"></span>
                                            </button>

                                        </td>

                                    </tr>
                                @endforeach
                                @if ($pasiens->isEmpty())
                                    <tr>
                                        <td colspan="100" class="text-center">Tidak ada data Pasien!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </ul>
                @if (!$pasiens->isEmpty())
                    <div class="mt-3 pagination-mobile">{{ $pasiens->withQueryString()->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Delete patient -->
    <div class="modal fade" id="deletePatient" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/admin/pasien/delete" method="post" id="formDeleteQueuePatient">
                <input type="hidden" name="codePatient" id="codeDeletePatient">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title text-primary fw-bold">Konfirmasi&nbsp;<i class='bx bx-check-shield fs-5'
                                style="margin-bottom: 3px;"></i></h5>
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal"><i
                                class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip"
                                data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
                    </div>
                    <div class="modal-body" style="margin-top: -10px;">
                        <div class="col-sm fs-6 namaPatientDelete"></div>
                    </div>
                    <div class="modal-footer" style="margin-top: -5px;">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i
                                class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tidak</button>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-trash fs-6'
                                style="margin-bottom: 3px;"></i>&nbsp;Ya, Hapus!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit patient -->

    {{-- <div class="modal fade" id="formModalAdminEditPatient" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/admin/pasien/edit" method="post" class="modalAdminEditPatient">
                @csrf
                <input type="hidden" name='code' value="{{ old('code') }}" id="codeEditPatient">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title text-primary fw-bold">Edit Data Pasien&nbsp;<i class='bx bx-user fs-5'
                                style="margin-bottom: 1px;"></i></h5>
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow cancelModalEditPatient"
                            data-bs-dismiss="modal"><i class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip"
                                data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col mb-2 mb-lg-3">
                                <label for="name" class="form-label required-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama pasien" autocomplete="off" required>
                                @error('name')
                                    <div class="invalid-feedback" style="margin-bottom: -3px;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-2 mb-lg-3">
                                <label for="address" class="form-label required-label">Alamat</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" autocomplete="off"
                                    placeholder="Masukkan alamat pasien. (max 255 karakter)" rows="4"
                                    required>{{ old('address') }}</input>
                                @error('address')
                                    <div class="invalid-feedback" style="margin-bottom: -3px;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col mb-2 mb-lg-3">
                                    <label for="birth" class="form-label required-label">Tanggal
                                        Lahir</label>
                                    <input type="date" id="tanggalLahir"
                                        class="form-control @error('birth') is-invalid @enderror" name="birth" autofocus
                                        value="{{ old('birth') }}" onfocus="hitungUmur()">
                                    @error('birth')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <div class="col mb-2 mb-lg-3">
                                        <label for="date_of_birth" class="form-label required-label">Umur</label>
                                        <input name="date_of_birth" id="date_of_birth" type="text"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            placeholder="" value="{{ old('date_of_birth') }}" readonly>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col">
                                    <label for="gender_patient" class="form-label required-label">Jenis Kelamin</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender"
                                        id="gender_patient" style="cursor: pointer;" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option id="laki-laki" @if (old('gender') == 'Laki-Laki') selected @endif
                                            value="Laki-Laki">Laki-Laki</option>
                                        <option id="perempuan" @if (old('gender') == 'Perempuan') selected @endif
                                            value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback" style="margin-bottom: -3px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <div class="col mb-2 mb-lg-3">
                                        <label for="phone" class="form-label required-label">Nomor
                                            Telepon/WA</label>
                                        <input type="text" id="phone"
                                            class="form-control Nomor-mask @error('phone') is-invalid @enderror"
                                            placeholder="Masukkan Nomor Telepon/WA" aria-label="Masukkan Nomor Telepon/WA"
                                            aria-describedby="basic-default-Nomor" name="phone"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback style="margin-bottom: -3px;>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger cancelModalEditPatient"
                            data-bs-dismiss="modal"><i class='bx bx-share fs-6'
                                style="margin-bottom: 3px;"></i>&nbsp;Batal</button>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-save fs-6'
                                style="margin-bottom: 3px;"></i>&nbsp;Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
--}}


    @include('sweetalert::alert')

    <script>
        //deletepasien
        $(".buttonDeletePatient").on("click", function() {
            const code = $(this).data("code");
            const name = $(this).data("name");
            $("#codeDeletePatient").val(code);
            $(".namaPatientDelete").html(
                "Hapus pasien atas nama <strong>" + name + "</strong> ?"
            );
            $("#deletePatient").modal("show");
        });

        ///filtering
        // $("#dateStart").on("change", function() {
        //     const data = $(this).val();
        //     const enddata = $("#endDate").val();
        //     if (enddata) {
        //         window.location.href =
        //             "/admin/pasien/filter?startDate=" + data + "&endDate=" + enddata;
        //     } else {
        //         window.location.href = "/admin/pasien/filter?startDate=" + data;
        //     }
        // });

        // $("#endDate").on("change", function() {
        //     const data = $("#dateStart").val();
        //     const enddata = $(this).val();
        //     if (data) {
        //         window.location.href =
        //             "/admin/pasien/filter?startDate=" + data + "&endDate=" + enddata;
        //     } else {
        //         $(this).val("");
        //         setMessage("Masukkan tanggal awal dulu!", "warning");
        //     }
        // });
    </script>
@section('script')
    <script src="{{ asset('assets/js/patients.js') }}"></script>
@endsection
@endsection
