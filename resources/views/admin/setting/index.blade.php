@extends('layouts.main.index')
@section('container')
    <style>
        .required-label::after {
            content: " *";
            color: red;
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
        </ol>
    </nav>
    <div class="row">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-profil" aria-controls="navs-profil"><i class="tf-icons bx bxs-user fs-6 me-1"
                            style="margin-bottom: 2px;"></i>&nbsp;Profil</button>
                </li>
            </ul>
            <div class="tab-content">
                <div>
                    <h5 class="card-header" style="margin-top: -0.5rem;">Profil Saya</h5>
                </div>
                <div class="card-body">
                    <form id="formAccountSettings" action="/admin/pengaturan" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="@if (Storage::disk('public')->exists('profil-images')) {{ asset('storage/' . auth()->user()->image) }} @else {{ asset('assets/img/profil-images-default/1.jpeg') }} @endif"
                                alt="profile" class="d-block rounded cursor-pointer fotoProfile" height="100"
                                width="100" id="uploadedPhotoProfil"
                                data-url-img="@if (Storage::disk('public')->exists('profil-images')) {{ asset('storage/' . auth()->user()->image) }} @else {{ asset('assets/img/profil-images-default/1.jpeg') }} @endif" />
                        </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="namaLengkap">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control  @error('name') is-invalid @enderror" id="namaLengkap"
                                name="name" autocomplete="off" placeholder="Enter your name"
                                value="{{ old('name') ?? auth()->user()->name }}" / readonly>
                            @error('name')
                                <div class="invalid-feedback" style="margin-bottom: -3px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="username">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control  @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') ?? auth()->user()->username }}"
                                autocomplete="off" / readonly>
                            @error('username')
                                <div class="invalid-feedback" style="margin-bottom: -3px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="email">Email&nbsp;<i
                                class='bx bx-edit fs-6 text-primary buttonEditEmailUser' data-bs-toggle="tooltip"
                                data-popup="tooltip-custom" data-bs-placement="auto" title="Email"></i></label>
                        <div class="col-sm-10">
                            <input type="email" id="email" class="form-control" name="email" autocomplete="off"
                                placeholder="Enter your email" disabled
                                value="{{ substr(auth()->user()->email, 0, 3) . str_repeat('*', strlen(substr(auth()->user()->email, 0, strpos(auth()->user()->email, '@'))) - 3) . substr(auth()->user()->email, -10) }}" />
                        </div>
                    </div>
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="name_app">Nama BPM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control  @error('name_app') is-invalid @enderror"
                                id="name_app" name="name_app" autocomplete="off" placeholder="Masukkan nama aplikasi"
                                value="{{ old('name_app') ?? $app[0]->name_app }}" / readonly>
                            @error('name_app')
                                <div class="invalid-feedback" style="margin-bottom: -3px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="description_app">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control @error('description_app') is-invalid @enderror" id="description_app"
                                name="description_app" autocomplete="off" placeholder="Masukkan deskripsi aplikasi disini. (max 255 karakter)"
                                rows="3" readonly>{{ old('description_app') ?? $app[0]->description_app }}</textarea>
                            @error('description_app')
                                <div class="invalid-feedback" style="margin-bottom: -3px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2 mb-lg-3">
                        <label class="col-sm-2 col-form-label" for="address_app">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address_app" name="address"
                                autocomplete="off" placeholder="Masukkan alamat klinik disini. (max 255 karakter)" rows="3" readonly>{{ old('address') ?? $app[0]->address }}</textarea>
                            @error('address')
                                <div class="invalid-feedback" style="margin-bottom: -3px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
