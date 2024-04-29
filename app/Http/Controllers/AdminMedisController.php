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
        // if ($request->filled('start_date') && $request->filled('end_date')) {
        //     $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
        //     $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
        //     $query->whereDate('created_at', '>=', $startDate)
        //         ->whereDate('created_at', '<=', $endDate);
        // }


        $rekamMedisList = Medis::with('pasien')
            ->filter($request->search)
            ->orderByDesc('created_at') // Gunakan scope untuk filter pencarian
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
                'pemeriksaan' => 'required',
                'kesimpulan' => 'required',
                'resep.*.id' => 'required',
                'resep.*.jumlah' => 'required|integer|min:1',
                'resep.*.aturan' => 'required',
            ],
            [
                'pasien' => 'Nama Pasien tidak boleh kosong!',
                'keluhan' => 'Keluhan tidak boleh kosong!',
                'pemeriksaan' => 'Hasil Pemeriksaan tidak boleh kosong!',
                'kesimpulan' => 'Kesimpulan tidak boleh kosong!',
            ]
        );


        DB::beginTransaction();
        try {
            $medis = new Medis([
                'pasien_id' => $request->pasien,
                'keluhan' => $request->keluhan,
                'pemeriksaan' => $request->pemeriksaan,
                'kesimpulan' => $request->kesimpulan,
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
        // $medis = Medis::with('pasien', 'obat')->get();
        // return view('admin.medis.ubah', [
        //     'app' => Application::all(),
        //     'tittle' => 'Ubah Rekan Medis',
        //     'medis' => $medis,
        // ], compact('medis'));

        $medis = Medis::with(
            'reseps.obat',
        )->findOrFail($id);

        // Ambil data lain yang dibutuhkan
        return view('admin.medis.ubah', [
            'app' => Application::all(),
            'tittle' => 'Ubah Rekam Medis',
            'medis' => $medis,
            'pasien' => Pasien::all(),
            'obats' => Obat::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $medisId)
    // {
    //     $request->validate(
    //         [
    //             'pasien' => 'required',
    //             'keluhan' => 'required',
    //             'resep.*.id' => 'required',
    //             'resep.*.jumlah' => 'required|integer|min:1',
    //             'resep.*.aturan' => 'required',
    //         ],
    //         [
    //             'pasien.required' => 'Nama Pasien tidak boleh kosong!',
    //             'keluhan.required' => 'Keluhan tidak boleh kosong!',
    //         ]
    //     );

    //     DB::beginTransaction();
    //     try {
    //         $medis = Medis::findOrFail($medisId);
    //         $medis->update([
    //             'pasien_id' => $request->pasien,
    //             'keluhan' => $request->keluhan,
    //         ]);

    //         // Sync resep
    //         $currentResepIds = $medis->reseps->pluck('obat_id')->all();
    //         $newResepIds = collect($request->resep)->pluck('id')->all();
    //         $toDelete = array_diff($currentResepIds, $newResepIds);
    //         $toAddOrUpdate = $request->resep;

    //         // Menghapus resep yang tidak ada lagi
    //         foreach ($toDelete as $id) {
    //             $medis->reseps()->where('obat_id', $id)->delete();
    //         }

    //         // Menambah atau memperbarui resep yang ada
    //         foreach ($toAddOrUpdate as $dataResep) {
    //             $obat = Obat::findOrFail($dataResep['id']);
    //             $jumlahLama = $medis->reseps()->where('obat_id', $dataResep['id'])->first()->jumlah ?? 0;
    //             if ($obat->stok + $jumlahLama < $dataResep['jumlah']) {
    //                 DB::rollback();
    //                 return back()->withInput()->withErrors('Stok ' . $obat->nama_obat . ' tidak mencukupi');
    //             }

    //             $medis->reseps()->updateOrCreate(
    //                 ['obat_id' => $dataResep['id']],
    //                 ['jumlah' => $dataResep['jumlah'], 'aturan' => $dataResep['aturan']]
    //             );

    //             $obat->increment('stok', $jumlahLama);
    //             $obat->decrement('stok', $dataResep['jumlah']);
    //         }

    //         DB::commit();
    //         return redirect('/admin/medis')->with('success', 'Data rekam medis telah diperbarui.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    //     }
    // }
    public function update(Request $request, $medisId)
    {
        $request->validate(
            [
                'pasien' => 'required',
                'keluhan' => 'required',
                'pemeriksaan' => 'required',
                'kesimpulan' => 'required',
                'resep.*.id' => 'required',
                'resep.*.jumlah' => 'required|integer|min:1',
                'resep.*.aturan' => 'required',
            ],
            [
                'pasien' => 'Nama Pasien tidak boleh kosong!',
                'keluhan' => 'Keluhan tidak boleh kosong!',
                'pemeriksaan' => 'Hasil Pemeriksaan tidak boleh kosong!',
                'kesimpulan' => 'Kesimpulan tidak boleh kosong!',
            ]
        );

        DB::beginTransaction();
        try {
            $medis = Medis::findOrFail($medisId);
            $medis->update([
                'pasien_id' => $request->pasien,
                'keluhan' => $request->keluhan,
                'pemeriksaan' => $request->pemeriksaan,
                'kesimpulan' => $request->kesimpulan,
            ]);

            $oldReseps = $medis->reseps;
            foreach ($oldReseps as $oldResep) {
                $oldResep->obat->increment('stok', $oldResep->jumlah);
            }

            $medis->reseps()->delete(); // Hapus semua resep lama

            foreach ($request->resep as $dataResep) {
                $obat = Obat::findOrFail($dataResep['id']);

                // Cek stok obat
                if ($obat->stok < $dataResep['jumlah']) {
                    throw new \Exception('Stok ' . $obat->nama_obat . ' tidak mencukupi');
                }

                $medis->reseps()->create([
                    'obat_id' => $dataResep['id'],
                    'jumlah' => $dataResep['jumlah'],
                    'aturan' => $dataResep['aturan'],
                ]);

                $obat->decrement('stok', $dataResep['jumlah']);
            }

            DB::commit();
            return redirect('/admin/medis')->with('success', 'Data rekam medis telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
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
        $searchTerm = $request->search;   // Kata kunci pencarian

        // Jika startDate atau endDate tidak tersedia, kembalikan pesan error
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir diperlukan.');
        }
        // Awal query dengan filter tanggal
        $query = Medis::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);

        // Jika ada kata kunci pencarian, tambahkan filter berdasarkan nama pasien
        if ($searchTerm) {
            $query->whereHas('pasien', function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Eksekusi query dan ambil hasilnya
        $rekamMedisList = $query->paginate(10)->withQueryString();

        // Kirim hasilnya ke view
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
        $searchTerm = $request->query('search');

        // Query berdasarkan filter yang disimpan di sesi
        $query = Medis::query();
        if ($searchTerm) {
            $query->whereHas('pasien', function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', '%' . $searchTerm . '%');
            });
        }
        // Terapkan filter berdasarkan filter yang disimpan dalam sesi
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if ($key === 'startDate') {
                    $startDate = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                } elseif ($key === 'endDate') {
                    $endDate = Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                } else {
                    $query->where($key, 'like', "%{$value}%");
                }
            }
        }

        // Jika ada pencarian berdasarkan nama pasien
        if (!empty($filters['search'])) {
            $query->filter($filters['search']);
        }

        // Buat PDF berdasarkan hasil query
        $rekamMedisList = $query->with('pasien')->get();

        $pdf = PDF::loadView('admin.pdf.medis', ['rekamMedisList' => $rekamMedisList]);
        return $pdf->download('laporan_rekam_medis.pdf');
    }
}
