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
                        <form action="" method="POST">
                            <div class="d-flex align-items-center">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="/admin/medis/create" type="button"
                                            class="btn btn-xs btn-dark fw-bold p-2">
                                            <i class='bx bx-add-to-queue'></i>&nbsp; Rekam Medis Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                </tbody>
                            </table>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>




    @include('sweetalert::alert')
@endsection
