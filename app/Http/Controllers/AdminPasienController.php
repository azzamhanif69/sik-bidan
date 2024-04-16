<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdminPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pasien.index', [
            'app' => Application::all(),
            'tittle' => 'Data Pasien',
            'pasiens' => Pasien::first()->filter(request(['search']))->paginate(10)
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
    public function show(string $id)
    {
        //
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

        // $idPatient = decrypt($request->code);
        // $validatedData = $request->validate([
        //     'no_rm' => 'required|string|max:8|unique:pasiens',
        //     'name' => 'required|string|max:255',
        //     'birth' => 'required',
        //     'address' => 'required',
        //     'date_of_birth' => 'required|integer|min:1',
        //     'gender' => 'required',
        //     'phone' => 'required|string|max:255'
        // ]);
        // Pasien::where('id', $idPatient)->update($validatedData);
        // return back()->with('success', 'Data pasien berhasil diubah!');
        // $rules = [
        //     'name' => 'required|string|max:255',
        //     'birth' => 'required',
        //     'address' => 'required',
        //     'date_of_birth' => 'required|integer|min:1',
        //     'gender' => 'required',
        //     'phone' => 'required|string|max:255'
        // ];
        // $validatedData = $request->validate($rules);
        // $validatedData['no_rm'] = auth()->user()->id;

        // Pasien::where('id', $pasien->id)->update($validatedData);
        // return redirect('/admin/pasien')->with('success', 'Data pasien berhasil diubah!');


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
    // public function editPatient(Request $request)
    // {
    //     $idPatient = decrypt($request->code);
    //     $validatedData = $request->validate([
    //         'no_rm' => 'required|string|max:8|unique:pasiens',
    //         'name' => 'required|string|max:255',
    //         'birth' => 'required',
    //         'address' => 'required',
    //         'date_of_birth' => 'required|integer|min:1',
    //         'gender' => 'required',
    //         'phone' => 'required|string|max:255'
    //     ]);
    //     Pasien::where('id', $idPatient)->update($validatedData);
    //     return back()->with('editPatientSuccess', 'Data pasien berhasil diubah!');
    // }

}
