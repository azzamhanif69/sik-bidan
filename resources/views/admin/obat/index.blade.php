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

        .select2-container--bootstrap .select2-selection--single {
            padding: 0.375rem 0.75rem;
            height: calc(2.25rem + 2px);
            border: 1px solid #ced4da;
        }

        .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            color: #495057;
        }

        .select2-container--bootstrap .select2-dropdown {
            border-color: #ced4da;
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Data Obat</li>
        </ol>
    </nav>
    <div class="flash-message" data-flash-message="@if (session()->has('success')) {{ session('success') }} @endif">
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
                    <div class="justify-content-start d-none d-md-block">
                        <form action="" method="POST">
                            <div class="d-flex align-items-center">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="/admin/obat/create" type="button" class="btn btn-xs btn-dark fw-bold p-2">
                                            <i class='bx bx-add-to-queue'></i>&nbsp; Obat Baru
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-xs btn-dark fw-bold p-2" data-toggle="modal"
                                            data-target="#tambahStokModal">
                                            <i class='bx bx-plus-medical'></i>&nbsp; Tambah Stok
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="justify-content-end">
                        <!-- Search -->
                        <form action="/admin/obat" method="GET">
                            <div class="input-group">
                                <input type="search" class="form-control" name="search" id="search"
                                    style="border: 1px solid #d9dee3;" value="{{ request('search') }}"
                                    placeholder="Cari data obat..." autocomplete="off" />
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
                                        <th class="text-white">Nama Obat</th>
                                        <th class="text-white">Sediaan</th>
                                        <th class="text-white">Dosis</th>
                                        <th class="text-white">Satuan</th>
                                        <th class="text-white text-center">Stok</th>
                                        <th class="text-white">Harga</th>
                                        <th class="text-white text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($obats as $index => $obat)
                                        <tr>
                                            <td>{{ $index + 1 }} </td>
                                            <td>{{ $obat->nama_obat }}</td>
                                            <td>{{ $obat->sediaan }}</td>
                                            <td class="">
                                                <span class="badge bg-label-secondary fw-bold">{{ $obat->dosis }}
                                                </span>
                                            </td>
                                            <td>{{ $obat->satuan }}</td>
                                            <td class="text-center"> <span
                                                    class="badge badge-center bg-dark rounded-pill">{{ $obat->stok }}</span>
                                            </td>
                                            <td>{{ $obat->harga }}</td>
                                            <td class="text-center">
                                                <a href="/admin/obat/{{ $obat->id }}/edit" type="button"
                                                    class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip"
                                                    data-popup="tooltip-custom" data-bs-placement="auto" title="Ubah Obat">
                                                    <span class="tf-icons bx bx-edit" style="font-size: 15px;"></span>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-icon btn-danger btn-sm buttonDeleteObat"
                                                    data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="auto" title="Hapus Obat"
                                                    data-code="{{ encrypt($obat->id) }}"
                                                    data-nama_obat="{{ $obat->nama_obat }}" id="buttonDeleteObat">
                                                    <span class="tf-icons bx bx-trash" style="font-size: 14px;"></span>
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($obats->isEmpty())
                                        <tr>
                                            <td colspan="100" class="text-center">Tidak ada data Obat!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </ul>
                    @if (!$obats->isEmpty())
                        <div class="mt-3 pagination-mobile">{{ $obats->withQueryString()->onEachSide(1)->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Modal Delete obat -->
    <div class="modal fade" id="deleteObat" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/admin/obat/delete" method="post" id="formDeleteQueueObat">
                <input type="hidden" name="codeObat" id="codeDeleteObat">
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
                        <div class="col-sm fs-6 namaObatDelete"></div>
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

    {{-- modal tambah stok --}}
    <div class="modal fade" id="tambahStokModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('obat.tambahStok') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary fw-bold">Tambah Stok Obat&nbsp;<i class='bx bxs-cart-add fs-5'
                                style="margin-bottom: 3px;"></i></h5>
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-dismiss="modal"><i
                                class="bx bx-x-circle text-danger fs-4" data-bs-toggle="tooltip"
                                data-popup="tooltip-custom" data-bs-placement="auto" title="Tutup"></i></button>
                    </div>
                    <div class="modal-body" style="margin-top: -10px;">
                        <!-- Form untuk menambah stok -->
                        <div class="row">
                            <div class="col mb-2 mb-lg-8">
                                <label for="obat" class="form-label required-label">Pilih Obat</label>
                                <select name="obat_id" id="obat-custom" class="form-control w-100 mt-2">
                                    <option></option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}">
                                            {{ $obat->nama_obat . ' ' . $obat->sediaan . ' ' . $obat->dosis . $obat->satuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-2 mb-lg-3">
                                <label for="jumlah" class="form-label required-label">Jumlah Stok</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    autocomplete="off" placeholder="Masukkan stok" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal"><i
                                class='bx bx-share fs-6' style="margin-bottom: 3px;"></i>&nbsp;Tidak</button>
                        <button type="submit" class="btn btn-primary"><i class='bx bxs-cart-add fs-6'
                                style="margin-bottom: 3px;"></i>&nbsp; Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    @include('sweetalert::alert')
    <script>
        //deleteobat
        $(".buttonDeleteObat").on("click", function() {
            const code = $(this).data("code");
            const nama_obat = $(this).data("nama_obat");
            $("#codeDeleteObat").val(code);
            $(".namaObatDelete").html(
                "Hapus obat dengan nama <strong>" + nama_obat + "</strong> ?"
            );
            $("#deleteObat").modal("show");
        });
        // tambahhobat
        $('#tambahStokModal').on('shown.bs.modal', function() {
            $('#obat-custom').select2({
                theme: "bootstrap",
                placeholder: "Pilih Obat",
                width: '100%',
                dropdownParent: $('#tambahStokModal'),
                ajax: {
                    url: '{{ route('cari.obat') }}', // Sesuaikan dengan URL endpoint Anda
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // parameter pencarian yang dikirim ke server
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return "Masukkan setidaknya 2 karakter";
                    },
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                },
                templateResult: function(obat) {
                    if (obat.loading) return obat.text; // Tampilkan teks loading atau teks pencarian
                    var $container = $(
                        "<div class='select2-result-obat clearfix'>" +
                        "   <div class='select2-result-obat__meta'>" +
                        "       <div class='select2-result-obat__title'></div>" +
                        "       <div class='select2-result-obat__description'></div>" +
                        "   </div>" +
                        "</div>"
                    );
                    // Set teks untuk judul dan deskripsi
                    $container.find(".select2-result-obat__title").text(obat.text).css('display',
                        'inline-block');
                    $container.find(".select2-result-obat__description").html(" " + obat.sediaan + " " +
                        obat.dosis + " " + obat.satuan).css('display', 'inline');
                    return $container;
                },
                templateSelection: function(obat) {
                    // Format teks yang ditampilkan setelah seleksi. Biasanya lebih sederhana.
                    return obat.text || obat
                        .nama_obat; // Gunakan obat.text atau properti lain yang sesuai dengan struktur data Anda
                }
            });
        });
    </script>
@endsection
