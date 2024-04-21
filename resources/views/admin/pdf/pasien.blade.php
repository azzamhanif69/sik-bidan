<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pasien</title>
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
    <h1>Laporan Pasien</h1>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>No. RM</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>Jenis Kelamin</th>
                <th>Nomor Telepon</th>
                <!-- Tambahkan kolom lain sesuai kebutuhan -->
            </tr>
        </thead>
        <tbody>
            @foreach ($pasiens as $index => $pasien)
                <tr>
                    <td>{{ $index + 1 }} </td>
                    <td>{{ $pasien->no_rm }}</td>
                    <td>{{ $pasien->name }}</td>
                    <td>{{ $pasien->address }}</td>
                    <td>{{ Carbon\Carbon::parse($pasien->birth)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                    <td>{{ $pasien->date_of_birth }}</td>
                    <td>{{ $pasien->gender }}</td>
                    <td>{{ $pasien->phone }}</td>
                    <!-- Tambahkan sel lain sesuai kebutuhan -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
