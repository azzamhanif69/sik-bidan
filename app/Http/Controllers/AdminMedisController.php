<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Medis;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.medis.index', [
            'app' => Application::all(),
            'tittle' => 'Rekam Medis',

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // if ($request->wantsJson()) {
        //     $data = [];
        //     if ($request->has('q')) {
        //         $data['pasien'] = Pasien::where('name', 'LIKE', '%' . $request->get('q') . '%')
        //             ->orWhere('no_rm', 'LIKE', '%' . $request->get('q') . '%')
        //             ->get();
        //         $data['resep'] = Obat::where('nama_obat', 'LIKE', '%' . $request->get('r') . '%')->get();
        //     }
        //     return response()->json($data, 200);
        // } else {
        //     $medis = Medis::with('pasien', 'obat')->get();
        //     return view('admin.medis.tambah', [
        //         'app' => Application::all(),
        //         'tittle' => 'Rekam Medis Baru',
        //         'medis' => $medis,
        //         'pasien' => Pasien::all(),
        //         'resep' => Obat::all()
        //     ]);
        // }

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
    public function store(Request $request)
    {

        // Validasi request
        $validated = $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            // Tambahkan validasi untuk field lainnya
        ]);

        // Proses penyimpanan data rekam medis baru dengan pasien_id
        $medis = new Medis;
        // Set atribut medis dari request
        $medis->pasien_id = $validated['pasien_id'];
        // Set atribut lainnya...
        $medis->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('nama.route.ke.halaman.tujuan')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
