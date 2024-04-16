<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.obat.index', [
            'app' => Application::all(),
            'tittle' => 'Data Obat',
            'obats' => Obat::first()->filter(request(['search']))->paginate(10)

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
}
