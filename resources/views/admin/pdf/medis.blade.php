<!DOCTYPE html>
<html>

<head>
    <title>Laporan Obat</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table thead th {
            background-color: #f2f2f2;
            color: #333;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #eaeaea;
        }
    </style>
</head>

<body>
    <h1>Laporan Rekam Medis</h1>
    <table class="table">
        <thead>
            <tr>
                <th class="text-white">No</th>
                <th class="text-white">No. RM</th>
                <th class="text-white">Nama Lengkap</th>
                <th class="text-white">Tanggal Periksa</th>
                <th class="text-white">Keluhan Utama</th>
                <th class="text-white">Hasil Pemeriksaan</th>
                <th class="text-white">Kesimpulan</th>
                <th class="text-white">Terapi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekamMedisList as $index => $medis)
                <!-- Tampilkan informasi rekam medis -->
                <tr>
                    <!-- Kita asumsikan kolom pasien_id menyimpan ID atau nama pasien -->
                    <td>{{ $loop->iteration }} </td>
                    <td><span class="badge bg-label-secondary fw-bold">{{ $medis->pasien->no_rm }}</span>
                    </td>
                    <td>{{ $medis->pasien->name }}</td>
                    <td class="text-justify">
                        {{ Carbon\Carbon::parse($medis->created_at)->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}WIB

                    </td>
                    <td>{{ $medis->keluhan }}</td>
                    <td>{{ $medis->pemeriksaan }}</td>
                    <td>{{ $medis->kesimpulan }}</td>
                    <td>
                        @foreach ($medis->reseps as $resep)
                            <span class="bullet">&bull;</span> {{ $resep->obat->nama_obat }}
                            {{ $resep->obat->sediaan }}
                            {{ $resep->obat->dosis }}{{ $resep->obat->satuan }},
                            {{ $resep->jumlah }} pcs : {{ $resep->aturan }}
                            @if (!$loop->last)
                                <br>
                            @endif
                        @endforeach
            @endforeach
            </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
