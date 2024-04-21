<?php

namespace App\Http\Controllers;

use App\Models\Medis;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\Pasien;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Medis::query(); // Awali query

        // Tambahkan filter berdasarkan rentang tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        }


        $rekamMedisList = Medis::with('pasien')
            ->filter($request->search) // Gunakan scope untuk filter pencarian
            ->paginate(10);
        return view('admin.medis.index', [
            'app' => Application::all(),
            'tittle' => 'Rekam Medis',
            'rekamMedisList' =>  $rekamMedisList
        ], compact('rekamMedisList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {


        $medis = Medis::with('pasien', 'obat')->get();
        return view('admin.medis.tambah', [
            'app' => Application::all(),
            'tittle' => 'Rekam Medis Baru',
            'medis' => $medis,
            'pasien' => Pasien::all(),
            'resep' => Obat::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pasien' => 'required',
    //         'keluhan' => 'required',
    //         'resep.*.id' => 'required',
    //         'resep.*.jumlah' => 'required|integer|min:1',
    //         'resep.*.aturan' => 'required',
    //     ]);

    //     // Mulai transaksi
    //     DB::beginTransaction();
    //     try {
    //         $pasien = Pasien::findOrFail($request->pasien);

    //         // Cek stok obat sebelum proses penyimpanan
    //         foreach ($request->resep as $resep) {
    //             $obat = Obat::findOrFail($resep['id']);
    //             if ($obat->stok < $resep['jumlah']) {
    //                 return back()->withInput()->withErrors('Stok ' . $obat->nama_obat . ' tidak mencukupi');
    //             }
    //         }
    //         // Jika stok semua obat cukup, proses penyimpanan
    //         foreach ($request->resep as $resep) {
    //             $obat = Obat::findOrFail($resep['id']);
    //             $medis = new Medis();
    //             $medis->pasien_id = $pasien->id;
    //             $medis->obat_id = $obat->id;
    //             $medis->keluhan = $request->keluhan;
    //             $medis->resep = $resep['nama_obat'];
    //             $medis->jumlah = $resep['jumlah'];
    //             $medis->aturan = $resep['aturan'];
    //             $medis->save();

    //             // Mengurangi stok obat
    //             $obat->decrement('stok', $resep['jumlah']);
    //         }

    //         // Commit transaksi
    //         DB::commit();
    //         return redirect('/admin/medis')->with('success', 'Data rekam medis telah disimpan.');
    //     } catch (\Exception $e) {
    //         // Jika terjadi kesalahan, rollback dan tampilkan error
    //         DB::rollback();
    //         return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    //     }
    // }
    public function store(Request $request)
    {
        $request->validate(
            [
                'pasien' => 'required',
                'keluhan' => 'required',
                'resep.*.id' => 'required',
                'resep.*.jumlah' => 'required|integer|min:1',
                'resep.*.aturan' => 'required',
            ],
            [
                'pasien' => 'Nama Pasien tidak boleh kosong!',
                'keluhan' => 'Keluhan tidak boleh kosong!',
            ]
        );


        DB::beginTransaction();
        try {
            $medis = new Medis([
                'pasien_id' => $request->pasien,
                'keluhan' => $request->keluhan,
            ]);
            $medis->save();

            foreach ($request->resep as $dataResep) {
                $obat = Obat::findOrFail($dataResep['id']);
                if ($obat->stok < $dataResep['jumlah']) {
                    DB::rollback();
                    return back()->withInput()->withErrors('Stok ' . $obat->nama_obat . ' tidak mencukupi');
                }

                $medis->reseps()->create([
                    'obat_id' => $dataResep['id'],
                    'jumlah' => $dataResep['jumlah'],
                    'aturan' => $dataResep['aturan'],
                ]);

                $obat->decrement('stok', $dataResep['jumlah']);
            }

            DB::commit();
            return redirect('/admin/medis')->with('success', 'Data rekam medis telah disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($pasienId)
    {
        // // Temukan pasien berdasarkan $pasienId
        // $pasien = Pasien::find($pasienId);

        // // Ambil semua rekam medis yang terkait dengan pasien ini
        // $rekamMedis = Medis::where('pasien_id', $pasienId)->with('reseps')->get();

        // // Sekarang $rekamMedis berisi semua entri rekam medis dan resep yang terkait
        // // Kirim data ini ke view
        // return view('pasien.show', compact('pasien', 'rekamMedis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function searchPatient(Request $request)
    {
        if ($request->wantsJson() && $request->has('q')) {
            $patients = Pasien::where('name', 'LIKE', '%' . $request->get('q') . '%')
                ->orWhere('no_rm', 'LIKE', '%' . $request->get('q') . '%')
                ->get();
            return response()->json(['pasien' => $patients], 200);
        }
    }

    public function searchPrescription(Request $request)
    {
        if ($request->wantsJson() && $request->has('q')) {
            $prescriptions = Obat::where('nama_obat', 'LIKE', '%' . $request->get('q') . '%')->get();
            return response()->json(['resep' => $prescriptions], 200);
        }
    }
    public function filters(Request $request)
    {
        $filters = $request->except(['_token', 'page']); // Menghilangkan token CSRF dan parameter halaman
        $request->session()->put('medis_filters', $filters);
        $startDate = $request->startDate; // Asumsi sudah dalam format 'Y-m-d'
        $endDate = $request->endDate;     // Asumsi sudah dalam format 'Y-m-d'

        // Jika startDate atau endDate tidak tersedia, kembalikan pesan error
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir diperlukan.');
        }

        // Format tanggal sesuai yang dibutuhkan dan query dengan whereBetween
        $startDate = Carbon::parse($startDate)->startOfDay();  // Mengatur ke awal hari
        $endDate = Carbon::parse($endDate)->endOfDay();        // Mengatur ke akhir hari

        $rekamMedisList = Medis::whereBetween('created_at', [$startDate, $endDate])->paginate(10)->withQueryString();

        return view('admin.medis.index', [
            'app' => Application::all(),
            'tittle' => 'Rekam Medis',
            'rekamMedisList' =>  $rekamMedisList,
        ], compact('rekamMedisList'));
    }
    public function downloadPDF(Request $request)
    {
        // Ambil filter dari sesi
        $filters = $request->session()->get('medis_filters', []);

        // Query berdasarkan filter yang disimpan di sesi
        $query = Medis::query();

        // Pastikan Anda mengonversi tanggal ke format yang sesuai sebelum digunakan dalam query
        if (!empty($filters['startDate'])) {
            $startDate = Carbon::createFromFormat('Y-m-d', $filters['startDate'])->startOfDay();
            $query->where('created_at', '>=', $startDate);
        }

        if (!empty($filters['endDate'])) {
            $endDate = Carbon::createFromFormat('Y-m-d', $filters['endDate'])->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }

        // Terapkan filter lain jika ada
        foreach ($filters as $key => $value) {
            if (!empty($value) && !in_array($key, ['startDate', 'endDate'])) {
                $query->where($key, 'like', "%{$value}%");
            }
        }

        $rekamMedisList = $query->get();

        $pdf = PDF::loadView('admin.pdf.medis', ['rekamMedisList' => $rekamMedisList]);
        return $pdf->download('laporan_rekam_medis.pdf');
    }
}
