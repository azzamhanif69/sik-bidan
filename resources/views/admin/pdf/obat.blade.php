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
    <h1>Laporan Obat</h1>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Stok Obat</th>
                <th>Harga Obat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obats as $index => $obat)
                <tr>
                    <td>{{ $index + 1 }} </td>
                    <td>{{ $obat->nama_obat . ' ' . $obat->sediaan . ' ' . $obat->dosis . '' . $obat->satuan }}</td>
                    <td>{{ $obat->stok }}</td>
                    <td>{{ $obat->harga }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
