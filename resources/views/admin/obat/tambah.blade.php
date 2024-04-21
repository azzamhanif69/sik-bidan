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
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/obat">Data Obat</a></li>
            <li class="breadcrumb-item active" aria-current="page">Baru</li>
        </ol>
    </nav>
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <a href="/admin/obat" class="btn btn-outline-danger"><i class='bx bx-left-arrow-alt'></i>&nbsp;Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/obat">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-rm">Nama Obat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('nama_obat') is-invalid @enderror"
                                id="basic-default-rm" placeholder="Masukkan Nama Obat" name="nama_obat" autofocus
                                value="{{ old('nama_obat') }}">
                            @error('nama_obat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="sediaan">Sediaan Obat</label>
                        <div class="col-sm-10">
                            <select class="form-select @error('sediaan') is-invalid @enderror" aria-label="sediaan"
                                id="sediaan" name="sediaan">
                                <option selected disabled>Pilih Sediaan Obat</option>
                                <option id="tablet" @if (old('sediaan') == 'Tablet') selected @endif value="Tablet">
                                    Tablet</option>
                                <option id="kapsul" @if (old('sediaan') == 'Kapsul') selected @endif value="Kapsul">
                                    Kapsul</option>
                                <option id="sirup" @if (old('sediaan') == 'Sirup') selected @endif value="Sirup">
                                    Sirup</option>
                            </select>
                            @error('sediaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="dosis">Dosis</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control @error('dosis') is-invalid @enderror" id="dosis"
                                placeholder="Masukkan Dosis" name="dosis" autofocus value="{{ old('dosis') }}">
                            @error('dosis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="satuan">Satuan Obat</label>
                        <div class="col-sm-10">
                            <select class="form-select @error('satuan') is-invalid @enderror" aria-label="satuan"
                                id="satuan" name="satuan">
                                <option selected disabled>Pilih Satuan Obat</option>
                                <option id="g" @if (old('satuan') == 'g') selected @endif value="g">
                                    g</option>
                                <option id="mg" @if (old('satuan') == 'mg') selected @endif value="mg">
                                    mg</option>
                                <option id="mcg" @if (old('satuan') == 'mcg') selected @endif value="mcg">
                                    mcg</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="stok">Stok</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok"
                                placeholder="Masukkan Stok" name="stok" autofocus value="{{ old('stok') }}">
                            @error('stok')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="harga">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                id="rupiahInput" placeholder="Masukkan Harga" name="harga" autofocus
                                value="{{ old('harga') }}">
                            @error('harga')
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
    @include('sweetalert::alert')

    <script>
        var rupiahInput = document.getElementById("rupiahInput");
        rupiahInput.addEventListener("keyup", function(e) {
            // Mengambil nilai dari input
            var value = this.value;
            // Menghapus semua karakter selain angka
            value = value.replace(/[^\d]/g, "");
            // Mengubah nilai menjadi format rupiah
            value = formatRupiah(value);
            // Memasukkan kembali nilai ke dalam input
            this.value = value;
        });

        function formatRupiah(angka) {
            var number_string = angka.toString();
            var split = number_string.split(",");
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Tambahkan titik jika sisa tidak sama dengan nol
            if (ribuan) {
                var separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            // Tambahkan koma untuk nilai desimal
            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;

            return "Rp " + rupiah;
        }
        var rupiahInput = document.getElementById("rupiahInput");
        rupiahInput.addEventListener("keyup", function(e) {
            // Mengambil nilai dari input
            var value = this.value;

            // Menghapus semua karakter selain angka
            value = value.replace(/[^\d]/g, "");

            // Mengubah nilai menjadi format rupiah
            value = formatRupiah(value);

            // Memasukkan kembali nilai ke dalam input
            this.value = value;
        });

        function formatRupiah(angka) {
            var number_string = angka.toString();
            var split = number_string.split(",");
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Tambahkan titik jika sisa tidak sama dengan nol
            if (ribuan) {
                var separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            // Tambahkan koma untuk nilai desimal
            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;

            return "Rp " + rupiah;
        }
    </script>
@endsection
