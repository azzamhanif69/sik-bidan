<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Pasien;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Request;

class AdminPDFController extends Controller
{
    public function generatePDF()
    {
        // Ambil data yang diperlukan dari database
        $pasiens = Pasien::all();

        // Buat objek Dompdf
        $dompdf = new Dompdf();

        // Load HTML ke Dompdf
        $dompdf->loadHtml(view('admin.pasien.index', compact('pasiens'))->render());
        // Render PDF

        // Output file PDF langsung ke browser
        return $dompdf->stream('laporan_pasien_bidan.pdf');
    }
    // Pastikan menggunakan facade PDF

    public function handleFilter(Request $request)
    {
        // Proses filter data dan simpan dalam sesi
        $filterData = $request->except('_token'); // mengambil semua data kecuali CSRF token
        $request->session()->put('filters', $filterData);

        // ... lakukan penyimpanan atau tindakan lainnya yang perlu
    }
    public function downloadPDF(Request $request)
    {
        // Ambil data filter dari sesi
        $filters = $request->session()->get('filters', []);

        // Lakukan query berdasarkan filter yang disimpan di sesi
        $pasiens = Pasien::query();

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $pasiens->where($key, $value);
            }
        }

        $pasiens = $pasiens->get();

        // Generate PDF
        $pdf = PDF::loadView('admin.pasien.index', ['pasiens' => $pasiens]);

        // Kembalikan PDF untuk diunduh
        return $pdf->download('pasiens.pdf');
    }
}
