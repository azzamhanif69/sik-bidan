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
                </form>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            $(document).ready(function() {
                $('#pasien').select2({
                    ajax: {
                        url: "{{ route('medis.create') }}", // Pastikan route ini benar
                        dataType: 'json',
                        data: function(params) {
                            return {
                                q: params.term // parameter pencarian untuk query
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.no_rm + ' | ' + item.name
                                    }; // Sesuaikan dengan struktur objek `pasien` Anda
                                })
                            };
                        }
                    },
                    placeholder: "Pilih Pasien",
                    allowClear: true,
                    theme: "bootstrap",
                    minimumInputLength: 2, // Minimal karakter sebelum melakukan request
                });
            });
        </script>
    @endpush
@endsection
