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
            <li class="breadcrumb-item"><a href="/admin/medis">Rekam Medis</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
    </nav>
    <div class="flash-message" data-flash-message="@if (session()->has('success')) {{ session('success') }} @endif">
    </div>
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <a href="/admin/medis" class="btn btn-outline-danger"><i class='bx bx-left-arrow-alt'></i>&nbsp;Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/medis/{{ $medis->id }}">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="pasien">Pasien</label>
                        <div class="col-sm-10">
                            <select name="pasien" id="pasien" class="form-control @error('pasien') is-invalid @enderror"
                                aria-label="Pasien">
                                <option value="">Pilih Pasien</option>
                                @foreach ($pasien as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('pasien', $medis->pasien->id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->no_rm . ' | ' . $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pasien')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="keluhan">Keluhan</label>
                        <div class="col-sm-10">
                            <input type="text" name="keluhan" id="keluhan"
                                class="form-control @error('keluhan') is-invalid @enderror" aria-label="keluhan"
                                value="{{ old('keluhan', $medis->keluhan) }}" placeholder="Masukkan Keluhan">
                            @error('keluhan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div id="multiInput">
                        <div class="inputSet">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required-label" for="resep">Resep</label>
                                <div class="col-sm-4">
                                    <select name="resep[]" id="resep"
                                        class="form-control @error('resep') is-invalid @enderror">
                                        <option value="">Pilih satu</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}"
                                                @if (old('resep') == $obat->id) selected @endif>
                                                {{ $obat->nama_obat . ' ' . $obat->sediaan . ' ' . $obat->dosis . ' ' . $obat->satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('resep')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-1 d-flex align-items-end">
                                    <a href="javascript:;" onclick="addresep()" type="button" name="addresep"
                                        id="addresep" class="btn btn-primary">Tambah</a>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <table class="table-responsive text-nowrap" style="border-radius: 3px;"width="100%"
                                            id="reseps"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-warning">Ubah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var i = 0;
        var f = 0;
        var existingReseps = @json($medis->reseps);
        var a = existingReseps.length;
        //perselect2 duniawi
        $(document).ready(function() {
            // Inisialisasi Select2 untuk pencarian pasien
            $('#pasien').select2({
                placeholder: "Pilih Pasien",
                allowClear: true,
                theme: "bootstrap",
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return "Masukkan setidaknya 2 karakter";
                    },
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                },
                ajax: {
                    url: '/search-patient', // Endpoint pencarian pasien
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // term pencarian
                        };
                    },
                    processResults: function(data) {
                        // Ubah data menjadi format yang dibutuhkan Select2
                        return {
                            results: data.pasien.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.no_rm + ' | ' + item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            // Inisialisasi Select2 untuk pencarian resep
            $('#resep').select2({
                placeholder: "Pilih Resep",
                allowClear: true,
                theme: "bootstrap",
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return "Masukkan setidaknya 2 karakter";
                    },
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                },
                ajax: {
                    url: '/search-prescription', // Endpoint pencarian resep
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // term pencarian
                        };
                    },
                    processResults: function(data) {
                        // Ubah data menjadi format yang dibutuhkan Select2
                        return {
                            results: data.resep.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama_obat + '   ' + item.sediaan + '   ' + item
                                        .dosis + '   ' + item.satuan
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });


        // Fungsi untuk menambahkan resep baru


        function addresep() {
            var res = $("#resep option:selected").html();
            var resid = $("#resep").val();
            if (resid !== null && resid !== "") {
                var newTableRow = `
        <div class="row mb-3">
            <div class="col-sm-2"><input type="hidden" name="resep[${f}][id]" value="${resid}" class="form-control" readonly></div>
            <div class="col-sm-6">
                <input type="text" name="resep[${f}][nama_obat]" value="${res}" class="form-control text-right" readonly>
            </div>
            <div class="col-sm-1">
                <input type="number" name="resep[${f}][jumlah]" placeholder="Qty" class="form-control" required>
            </div>
            <div class="col-sm-2">
                <input type="text" name="resep[${f}][aturan]" placeholder="Aturan pakai" class="form-control" required>
            </div>
            <div class="col-sm-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger remove-res">Hapus</button>
            </div>
        </div>
        `;
                $("#reseps").append(newTableRow);
                $("#resep").val(null).trigger('change');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih resep terlebih dahulu.'
                });
            }
            ++a;
        }
        $(document).on('click', '.remove-res', function() {
            $(this).closest('.row').remove();
        });

        // Fungsi untuk memuat resep yang sudah ada
        function loadExistingReseps() {
            existingReseps.forEach(function(resep, index) {
                var resepHTML = `
                <div class="row mb-3">
                    <div class="col-sm-2"><input type="hidden" name="resep[${index}][id]" value="${resep.obat_id}" class="form-control" readonly></div>
                    <div class="col-sm-6">
                        <input type="text" name="resep[${index}][nama_obat]" value="${resep.obat.nama_obat}" class="form-control text-right" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="number" name="resep[${index}][jumlah]" value="${resep.jumlah}" class="form-control" required>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="resep[${index}][aturan]" value="${resep.aturan}" class="form-control" required>
                    </div>
                    <div class="col-sm-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-res">Hapus</button>
                    </div>
                </div>
            `;
                $("#reseps").append(resepHTML);
            });
        }

        // Panggil fungsi ketika dokumen siap
        $(document).ready(function() {
            loadExistingReseps();
        });

        // Event handler untuk menghapus resep
        $(document).on('click', '.remove-res', function() {
            $(this).closest('.row').remove();
        });
    </script>
    @include('sweetalert::alert')
@endsection
