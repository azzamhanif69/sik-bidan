<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Medis;
use App\Models\Pasien;
use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;


class AdminPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Di dalam controller

    public function index(Request $request)
    {
        $filters = $request->only('search'); // Ambil filter dari request
        $pasiens = Pasien::Cari($filters)->orderByDesc('created_at')->paginate(10); // Terapkan filter pada query

        return view('admin.pasien.index', [
            'app' => Application::all(),
            'tittle' => 'Data Pasien',
            'pasiens' => $pasiens,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cek = Pasien::count();
        if ($cek == 0) {
            $urut = 100001;
        } else {
            $ambil = Pasien::all()->last();
            $urut = (int)substr($ambil->no_rm, -6) + 1;
            $nomor =  $urut;
        }
        return view('admin.pasien.tambah', [
            'app' => Application::all(),
            'tittle' => 'Pasien Baru',
            'pasien' => Pasien::all(),
            'nomor' => $nomor
        ], compact('nomor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate(
            [
                'no_rm' => 'required|string|max:8|unique:pasiens',
                'name' => 'required|string|max:255',
                'birth' => 'required',
                'address' => 'required',
                'date_of_birth' => 'required|integer|min:1',
                'gender' => 'required',
                'phone' => 'required|string|max:255'
            ],
            [
                'no_rm' => 'Nomor RM tidak boleh kosong!',
                'name' => 'Nama Pasien tidak boleh kosong!',
                'address' => 'Alamat Pasien tidak boleh kosong!',
                'birth' => 'Tanggal lahir harus dipilih!',
                'gender' => 'Jenis kelamin harus dipilih!',
                'phone' => 'Nomor Telepon/WA tidak boleh kosong!',
                'date_of_birth' => 'Pilih tanggal lahir telebih dahulu!'
            ]
        );
        Pasien::create($validatedData);
        return redirect('/admin/pasien')->with('success', 'Pasien Baru Ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pasien = Pasien::findOrFail($id);
        $rekamMedis = Medis::where('pasien_id', $pasien->id)->paginate(10); // Ambil rekam medis khusus untuk pasien ini

        return view('admin.pasien.show', [
            'app' => Application::all(),
            'tittle' => 'Rekam Medis',
            'pasien' => $pasien,
            'rekamMedis' => $rekamMedis, // Kirim data rekam medis ke view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('admin.pasien.ubah', [
            'app' => Application::all(),
            'tittle' => 'Pasien Ubah',
            'pasien' => $pasien
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());
        return redirect('/admin/pasien')->with('success', 'Pasien Berhasil Diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
    }
    public function deletePatient(Request $request)
    {
        $idPatient = decrypt($request->codePatient);
        Pasien::destroy($idPatient);
        return back()->with('success', 'Pasien berhasil dihapus!');
    }


    public function filters(Request $request)
    {
        $filters = $request->except(['_token', 'page']); // Menghilangkan token CSRF dan parameter halaman
        $request->session()->put('pasien_filters', $filters);

        $startDate = $request->startDate; // Asumsi sudah dalam format 'Y-m-d'
        $endDate = $request->endDate;     // Asumsi sudah dalam format 'Y-m-d'

        // Jika startDate atau endDate tidak tersedia, kembalikan pesan error
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir diperlukan.');
        }

        // Format tanggal sesuai yang dibutuhkan dan query dengan whereBetween
        $startDate = Carbon::parse($startDate)->startOfDay();  // Mengatur ke awal hari
        $endDate = Carbon::parse($endDate)->endOfDay();        // Mengatur ke akhir hari

        $pasiens = Pasien::whereBetween('created_at', [$startDate, $endDate])->paginate(10)->withQueryString();


        return view('admin.pasien.index', [
            'app' => Application::all(),
            'tittle' => 'Data Pasien',
            'pasiens' => $pasiens,
        ], compact('pasiens'));
    }

    public function downloadPDF(Request $request)
    {
        // Ambil filter dari sesi
        $filters = $request->session()->get('pasien_filters', []);

        // Query berdasarkan filter yang disimpan di sesi
        $query = Pasien::query();

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

        $pasiens = $query->get();

        $pdf = PDF::loadView('admin.pdf.pasien', ['pasiens' => $pasiens]);
        return $pdf->download('laporan_pasien.pdf');
    }
}
