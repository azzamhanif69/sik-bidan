<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Di dalam controller

    public function index(Request $request)
    {
        $filters = $request->only('search'); // Ambil filter dari request
        $obats = Obat::filter($filters)->orderByDesc('created_at')->paginate(10); // Terapkan filter pada query

        return view('admin.obat.index', [
            'app' => Application::all(),
            'tittle' => 'Data Obat',
            'obats' => $obats,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.obat.tambah', [
            'app' => Application::all(),
            'tittle' => 'Obat Baru',
            'obat' => Obat::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_obat' => 'required|string',
            'sediaan' => 'required|string',
            'dosis' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'stok' => 'required|integer|min:1',
            'harga' => 'required|string',
        ], [
            'nama_obat' => 'Nama Obat tidak boleh kosong!',
            'sediaan' => 'Sediaan harus dipilih!',
            'dosis' => 'Dosis Obat tidak boleh kosong!',
            'satuan' => 'Satuan Obat harus dipilih!',
            'stok' => 'Stok obat tidak boleh kosong!',
            'harga' => 'Harga Obat tidak boleh kosong!'
        ]);
        Obat::create($validatedData);
        return redirect('/admin/obat')->with('success', 'Obat Telah Ditambahkan!');
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
    public function edit(Obat $obat)
    {
        return view('admin.obat.ubah', [
            'app' => Application::all(),
            'tittle' => 'Obat Ubah',
            'obat' => $obat
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->update($request->all());
        return redirect('/admin/obat')->with('success', 'Obat Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function deleteObat(Request $request)
    {
        $idObat = decrypt($request->codeObat);
        Obat::destroy($idObat);
        return back()->with('success', 'Obat berhasil dihapus!');
    }
    public function tambahStok(Request $request)
    {
        $obat = Obat::findOrFail($request->obat_id);
        $obat->stok += $request->jumlah;
        $obat->save();
        return redirect()->back()->with('success', 'Stok obat berhasil ditambahkan.', compact('obat'));
    }
    public function cariObat(Request $request)
    {
        $keyword = $request->get('q');
        $obat = Obat::where('nama_obat', 'LIKE', "%$keyword%")
            ->get(['id', 'nama_obat', 'sediaan', 'dosis', 'satuan'])
            ->map(function ($obat) {
                return [
                    'id' => $obat->id,
                    'text' => $obat->nama_obat,
                    'sediaan' => $obat->sediaan,
                    'dosis' => $obat->dosis,
                    'satuan' => $obat->satuan
                ];
            });

        return response()->json(['results' => $obat]);
    }
    public function filters(Request $request)
    {
        $filters = $request->except(['_token', 'page']); // Menghilangkan token CSRF dan parameter halaman
        $request->session()->put('obat_filters', $filters);
        $startDate = $request->startDate; // Asumsi sudah dalam format 'Y-m-d'
        $endDate = $request->endDate;     // Asumsi sudah dalam format 'Y-m-d'

        // Jika startDate atau endDate tidak tersedia, kembalikan pesan error
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir diperlukan.');
        }

        // Format tanggal sesuai yang dibutuhkan dan query dengan whereBetween
        $startDate = Carbon::parse($startDate)->startOfDay();  // Mengatur ke awal hari
        $endDate = Carbon::parse($endDate)->endOfDay();        // Mengatur ke akhir hari

        $obats = Obat::whereBetween('created_at', [$startDate, $endDate])->paginate(10)->withQueryString();

        return view('admin.obat.index', [
            'app' => Application::all(),
            'tittle' => 'Data Obat',
            'obats' => $obats,
        ], compact('obats'));
    }
    public function downloadPDF(Request $request)
    {
        // Ambil filter dari sesi
        $filters = $request->session()->get('obat_filters', []);

        // Query berdasarkan filter yang disimpan di sesi
        $query = Obat::query();

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

        $obats = $query->get();

        $pdf = PDF::loadView('admin.pdf.obat', ['obats' => $obats]);
        return $pdf->download('laporan_obat.pdf');
    }
}
