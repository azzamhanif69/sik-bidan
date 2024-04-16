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
            <li class="breadcrumb-item"><a href="/admin/pasien">Data Pasien</a></li>
            <li class="breadcrumb-item active" aria-current="page">Baru</li>
        </ol>
    </nav>
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <a href="/admin/pasien" class="btn btn-outline-danger"><i class='bx bx-left-arrow-alt'></i>&nbsp;Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/pasien">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-rm">No RM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('no_rm') is-invalid @enderror"
                                id="basic-default-rm" placeholder="Masukkan Nomor RM" name="no_rm" autofocus
                                value="{{ $nomor }}" readonly>
                            @error('no_rm')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-name">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="basic-default-name" placeholder="Masukkan Nama" name="name" autofocus
                                value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-Alamat">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="basic-default-Alamat" placeholder="Masukkan Alamat" name="address" autofocus
                                value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-TanggalLahir">Tanggal
                            Lahir</label>
                        <div class="col-sm-2">
                            <input type="date" id="tanggalLahir"
                                class="form-control @error('birth') is-invalid @enderror" id="basic-default-TanggalLahir"
                                name="birth" autofocus value="{{ old('birth') }}" onfocus="hitungUmur()">
                            {{-- <button type="button" onclick="hitungUmur()">Hitung Umur</button> --}}
                            @error('birth')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-umur">Umur</label>
                        <div class="col-sm-10">
                            <input name="date_of_birth"id="hasilUmur" type="text"
                                class="form-control @error('date_of_birth') is-invalid @enderror" id="basic-default-umur"
                                placeholder="" value="{{ old('date_of_birth') }}" readonly>
                            @error('date_of_birth')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-Jenis">Jenis
                            Kelamin</label>
                        <div class="col-sm-10">
                            <select class="form-select @error('gender') is-invalid @enderror"
                                aria-label="basic-default-Jenis" id="basic-default-Jenis" name="gender">
                                <option selected disabled>Pilih Jenis Kelamin</option>
                                <option id="laki-laki" @if (old('gender') == 'Laki-laki') selected @endif value="Laki-laki">
                                    Laki-Laki</option>
                                <option id="perempuan" @if (old('gender') == 'Perempuan') selected @endif value="Perempuan">
                                    Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label required-label" for="basic-default-Nomor">Nomor
                            Telepon/WA</label>
                        <div class="col-sm-10">
                            <input type="text" id="basic-default-Nomor"
                                class="form-control Nomor-mask @error('phone') is-invalid @enderror"
                                placeholder="Masukkan Nomor Telepon/WA" aria-label="Masukkan Nomor Telepon/WA"
                                aria-describedby="basic-default-Nomor" name="phone" value="{{ old('phone') }}">
                            @error('phone')
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
    <script type="text/javascript">
        document.getElementById('tanggalLahir').addEventListener('change', function() {
            var tanggalLahir = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - tanggalLahir.getFullYear();
            var monthDiff = today.getMonth() - tanggalLahir.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < tanggalLahir.getDate())) {
                age--;
            }

            document.getElementById('hasilUmur').value = age;
        });
    </script>
@endsection
