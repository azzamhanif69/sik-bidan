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
            <li class="breadcrumb-item active" aria-current="page">Baru</li>
        </ol>
    </nav>
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
                                        {{ $p->no_rm . ' | ' . $p->name }}</option>
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
                        <label class="col-sm-2 col-form-label" for="keluhan">Keluhan</label>
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
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="resep">Resep</label>
                        <div class="col-sm-10">
                            <select name="resep" id="resep"
                                class="form-control select2 @error('resep') is-invalid @enderror" aria-label="resep">
                                <option></option>
                                @foreach ($resep as $r)
                                    <option value="{{ $r->id }}" @if (old('resep') == $r->id) selected @endif>
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
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="aturan">Aturan</label>
                        <div class="col-sm-10">
                            <input type="text" name="aturan" id="aturan"
                                class="form-control @error('aturan') is-invalid @enderror" aria-label="aturan"
                                value="{{ old('aturan') }}" placeholder="Masukkan Aturan">
                            @error('aturan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="jumlah">Jumlah</label>
                        <div class="col-sm-10">
                            <input type="number" name="jumlah" id="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror" aria-label="jumlah"
                                value="{{ old('jumlah') }}" placeholder="Masukkan Jumlah">
                            @error('jumlah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
    @push('script')
        <script>
            $(document).ready(function() {
                // Inisialisasi Select2 untuk pencarian pasien
                $('#pasien').select2({
                    placeholder: "Pilih Pasien",
                    allowClear: true,
                    theme: "bootstrap",
                    minimumInputLength: 2,
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
        </script>
    @endpush
@endsection
