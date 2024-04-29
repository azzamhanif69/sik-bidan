<?php

namespace App\Http\Controllers;

use App\Charts\CountObatChart;
use App\Models\Pasien;
use App\Models\Application;
use App\Models\Obat;
use App\Models\Medis;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(CountObatChart $chart)
    {
        // $patientIds = Pasien::pluck('id')->toArray();
        // $results = Pasien::whereIn('id', $patientIds)->get();
        // $count = $results->count();
        // return view('admin.dashboard.index', [
        //     'app' => Application::all(),
        //     'tittle' => 'Dashboard',
        //     'totalPatient' => $count

        // ]);
        $totalPatient = Pasien::count();

        // Menghitung total kunjungan hari ini
        $todayVisits = Medis::whereDate('created_at', today())->count();

        // Menghitung total pasien laki-laki
        $totalMalePatients = Pasien::where('gender', 'Laki-laki')->count();

        // Menghitung total pasien perempuan
        $totalFemalePatients = Pasien::where('gender', 'Perempuan')->count();
        //menghitung obat terabanyak
        // $topObats = Obat::withCount(['reseps as usage_count' => function ($query) {
        //     $query->select(DB::raw("sum(jumlah)")); // asumsikan kolom 'jumlah' ada di tabel 'reseps'
        // }])->orderBy('usage_count', 'desc')
        //     ->take(5)
        //     ->get();
        // Pastikan 'chart' hanya berisi object chart yang ingin dikirim ke view
        $obatChart = $chart->countObat();
        $keluhanChart = $chart->countKeluhan();
        $usiaChart = $chart->countUsia();

        return view('admin.dashboard.index', [
            'app' => Application::all(),
            'tittle' => 'Dashboard', // Ada kesalahan ketik di 'tittle', harusnya 'title'
            'totalPatient' => $totalPatient,
            'todayVisits' => $todayVisits,
            'totalMalePatients' => $totalMalePatients,
            'totalFemalePatients' => $totalFemalePatients,
            // 'topObats' => $topObats,
            'obatChart' => $obatChart,
            'keluhanChart' => $keluhanChart,
            'usiaChart' => $usiaChart
        ]);
    }
}
