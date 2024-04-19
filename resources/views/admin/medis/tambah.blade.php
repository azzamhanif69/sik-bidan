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

        /* .text-right {
                                                                                                                                                                            text-align: right;
                                                                                                                                                                        } */

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
            <li class="breadcrumb-item active" aria-current="page">Baru</li>
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
                <form method="POST" action="/admin/medis">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="pasien">Pasien</label>
                        <div class="col-sm-10">
                            <select name="pasien" id="pasien" class="form-control @error('pasien') is-invalid @enderror"
                                aria-label="pasien">
                                <option></option>
                                @foreach ($pasien as $p)
                                    <option value="{{ $p->id }}" @if (old('pasien') == $p->id) selected @endif>
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
                                value="{{ old('keluhan') }}" placeholder="Masukkan Keluhan">
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
                                        class="form-control select2 @error('resep') is-invalid @enderror"
                                        aria-label="resep">
                                        <option></option>
                                        @foreach ($resep as $r)
                                            <option value="{{ $r->id }}"
                                                @if (old('resep') == $r->id) selected @endif>
                                                {{ $r->nama_obat . ' ' . $r->sediaan . ' ' . $r->dosis . ' ' . $r->satuan }}
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
                                    {{-- <button type="button" class="btn btn-primary addInput">+</button> --}}
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <table class="table-responsive text-nowrap" style="border-radius: 3px;"width="100%"
                                            id="reseps"></table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required-label" for="aturan">Aturan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="aturan[]" id="aturan"
                                        class="form-control @error('aturan') is-invalid @enderror" aria-label="aturan"
                                        value="{{ old('aturan[]') }}" placeholder="Masukkan Aturan Pakai">
                                    @error('aturan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required-label" for="Qty">Qty</label>
                                <div class="col-sm-1">
                                    <input type="number" name="jumlah[]" id="jumlah"
                                        class="form-control @error('jumlah') is-invalid @enderror" aria-label="jumlah"
                                        value="{{ old('jumlah[]') }}" placeholder="Qty">
                                    @error('jumlah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- @push('script') --}}
    <script type="text/javascript">
        var i = 0;
        var a = 0;

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


        function addresep() {
            var res = $("#resep option:selected").html();
            var resid = $("#resep").val();
            if (resid !== null && resid !== "") {
                var newTableRow = `
        <div class="row mb-3">
            <div class="col-sm-2"><input type="hidden" name="resep[${a}][id]" value="${resid}" class="form-control" readonly></div>
            <div class="col-sm-6">
                <input type="text" name="resep[${a}][nama_obat]" value="${res}" class="form-control text-right" readonly>
            </div>
            <div class="col-sm-1">
                <input type="number" name="resep[${a}][jumlah]" placeholder="Qty" class="form-control" required>
            </div>
            <div class="col-sm-2">
                <input type="text" name="resep[${a}][aturan]" placeholder="Aturan pakai" class="form-control" required>
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

        //     $(document).ready(function() {
        //         // Ketika tombol Tambah Input diklik
        //         $(document).on('click', '.addInput', function() {
        //             var inputSet = `
    //     <div class="inputSet">
    //         <div class="row mb-3">
    //             <label class="col-sm-2 col-form-label required-label" for="resep">Resep</label>
    //             <div class="col-sm-4">
    //                 <select name="resep[]" class="form-control select2" aria-label="resep">
    //                     <option></option>
    //                     @foreach ($resep as $r)
    //                         <option value="{{ $r->id }}">{{ $r->nama_obat . ' ' . $r->sediaan . ' ' . $r->dosis . ' ' . $r->satuan }}</option>
    //                     @endforeach
    //                 </select>
    //             </div>
    //         </div>
    //         <div class="row mb-3">
    //             <label class="col-sm-2 col-form-label required-label" for="aturan">Aturan</label>
    //             <div class="col-sm-4">
    //                 <input type="text" name="aturan[]" class="form-control" aria-label="aturan" placeholder="Masukkan Aturan Pakai">
    //             </div>
    //         </div>
    //         <div class="row mb-3">
    //             <label class="col-sm-2 col-form-label required-label" for="jumlah">Qty</label>
    //             <div class="col-sm-1">
    //                 <input type="number" name="jumlah[]" class="form-control" aria-label="jumlah" placeholder="Qty">
    //             </div>
    //             <div class="col-md-3 mb-1 d-flex align-items-end">
    //                 <button type="button" class="btn btn-danger removeInput" id="hapusResep">-</button>
    //             </div>
    //         </div>
    //     </div>
    // `;
        //             // Tambahkan input set ke div #multiInput
        //             $('#multiInput').append(inputSet);

        //             // Inisialisasi Select2 dengan AJAX pada elemen Select2 yang baru ditambahkan
        //         });

        //         // Menghapus input set yang dipilih
        //         $(document).on('click', '.removeInput', function() {
        //             $(this).closest('.inputSet').remove();
        //         });
        //     });
    </script>
    {{-- @endpush --}}
    @include('sweetalert::alert')
@endsection
