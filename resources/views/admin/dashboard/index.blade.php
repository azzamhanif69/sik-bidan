@extends('layouts.main.index')
@section('container')
    <style>
        .apexcharts-legend-series {
            display: none;
        }

        .apexcharts-title-text {
            font-size: 1rem;
            font-weight: 700 !important;
        }
    </style>
    <div class="row">
        <div class="col-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="row p-2 p-lg-0">
                        <div class="col-md-4">
                            <div class="stats-icon" style="background-color: #001e80;">
                                <i class="bx bx-group text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted mt-3 mt-lg-0 fw-bold mb-2">Total Pasien</h6>
                            <h6 class="mb-0 fw-bold">0</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
