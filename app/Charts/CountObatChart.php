<?php

namespace App\Charts;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\Medis;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class CountObatChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function countObat()
    {
        $obats = Obat::withCount(['reseps as usage_count' => function ($query) {
            $query->select(DB::raw("sum(jumlah)"));
        }])->orderBy('usage_count', 'desc')
            ->take(5)
            ->get();

        $names = $obats->pluck('nama_obat')->toArray();
        $counts = $obats->pluck('usage_count')->toArray();

        $chart = (new LarapexChart)->barChart()
            ->setTitle('Stok Obat Terpakai')
            ->addData('Stok Terpakai', $counts)
            ->setFontFamily('Poppins')
            ->setFontColor('#566a7f')
            ->setColors(['#696cff', '#ff6384'])
            ->setXAxis($names);


        // Tidak perlu 'return $this->chart->lineChart()'
        // Cukup kembalikan chart yang telah dibuat
        return $chart;
    }
    public function countKeluhan()
    {
        $topKeluhan = Medis::select('keluhan', DB::raw('count(*) as jumlah'))
            ->groupBy('keluhan')
            ->orderBy('jumlah', 'desc')
            ->take(10)
            ->get();

        $keluhanNames = $topKeluhan->pluck('keluhan')->toArray();
        $keluhanCounts = $topKeluhan->pluck('jumlah')->toArray();

        return (new LarapexChart)->lineChart()
            ->setTitle('10 Besar Penyakit Berdasarkan Keluhan')
            ->addData('Jumlah Keluhan', $keluhanCounts)
            ->setFontFamily('Poppins')
            ->setFontColor('#566a7f')
            ->setColors(['#696cff', '#ff6384'])
            ->setXAxis($keluhanNames);
    }
    private function determineAgeGroup($age)
    {
        if ($age < 1) {
            return '< 1 tahun';
        } elseif ($age >= 1 && $age <= 5) {
            return '1-5 tahun';
        } elseif ($age >= 6 && $age <= 14) {
            return '6-14 tahun';
        } elseif ($age >= 15 && $age <= 45) {
            return '15-45 tahun';
        } elseif ($age >= 46 && $age <= 55) {
            return '46-55 tahun';
        } else {
            return '> 55 tahun';
        }
    }
    public function countUsia()
    {
        $today = now()->toDateString();

        $medisRecords = Medis::with(['pasien' => function ($query) {
            $query->whereNotNull('birth')
                ->select('id', 'birth');
        }])->get();

        $visitCounts = $medisRecords->mapToGroups(function ($medis) use ($today) {
            if (!is_null($medis->pasien)) {
                $age = \Carbon\Carbon::parse($medis->pasien->birth)->age;
                $ageGroup = $this->determineAgeGroup($age);
                return [$ageGroup => 1];
            }
        })->map(function ($group) {
            return count($group);
        });

        // Urutkan keys berdasarkan urutan yang Anda inginkan
        $sortedAgeGroups = [
            '< 1 tahun',
            '1-5 tahun',
            '6-14 tahun',
            '15-45 tahun',
            '46-55 tahun',
            '> 55 tahun'
        ];

        // Pastikan semua kelompok usia ada dalam array dengan nilai default
        $visitCounts = array_merge(array_fill_keys($sortedAgeGroups, 0), $visitCounts->toArray());

        // Sekarang urutkan visitCounts berdasarkan urutan yang ada di $sortedAgeGroups
        $sortedVisitCounts = [];
        foreach ($sortedAgeGroups as $ageGroup) {
            $sortedVisitCounts[$ageGroup] = $visitCounts[$ageGroup];
        }


        // Membangun grafik dengan data yang sudah diurutkan
        $chart = (new LarapexChart)->lineChart()
            ->setTitle('Kunjungan Berdasarkan Kelompok Usia')
            ->addData('Jumlah Kunjungan', array_values($sortedVisitCounts))
            ->setFontFamily('Poppins')
            ->setFontColor('#566a7f')
            ->setColors(['#696cff', '#ff6384'])
            ->setXAxis(array_keys($sortedVisitCounts));

        return $chart;
    }
}
