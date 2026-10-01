<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Stok - {{ $produk->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .product-info {
            margin-bottom: 30px;
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
        }

        .product-info table {
            width: 100%;
        }

        .product-info td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .product-info td:first-child {
            font-weight: bold;
            width: 30%;
        }

        .summary-boxes {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9fafb;
        }

        .summary-box h3 {
            font-size: 24px;
            margin: 10px 0;
            color: #2563eb;
        }

        .summary-box p {
            color: #666;
            font-size: 11px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 4px solid #2563eb;
            padding-left: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }

        .action-buttons {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #2563eb;
            color: white;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        @media print {
            .action-buttons {
                display: none !important;
            }

            body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="header">
        <h1>Rumah Jahit Ria</h1>
        <p>Riwayat Stok Produk</p>
        <p>Tanggal Cetak: {{ date("d F Y H:i:s") }}</p>
    </div>

    <div class="product-info">
        <table>
            <tr>
                <td>Nama Produk</td>
                <td>: {{ $produk->nama }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>: {{ $produk->kategori->nama ?? "-" }}</td>
            </tr>
            <tr>
                <td>Harga</td>
                <td>: Rp {{ number_format($produk->harga, 0, ",", ".") }}</td>
            </tr>
            <tr>
                <td>Total Stok Saat Ini</td>
                <td>: <strong style="color: #2563eb;">{{ $totalStok }} unit</strong></td>
            </tr>
        </table>
    </div>

    <div class="summary-boxes">
        <div class="summary-box">
            <p>Stok Produk Utama</p>
            <h3>{{ $produk->jumlah_produk ?? 0 }}</h3>
            <p>Unit</p>
        </div>
        <div class="summary-box">
            <p>Stok Jenis/Variasi</p>
            <h3>{{ $produk->jenisProduk->sum("jumlah_produk") }}</h3>
            <p>Unit</p>
        </div>
        <div class="summary-box">
            <p>Total Stok</p>
            <h3>{{ $totalStok }}</h3>
            <p>Unit</p>
        </div>
    </div>

    @if ($produk->jenisProduk->count() > 0)
        <div class="section-title">Detail Stok Per Jenis/Variasi</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Nama Jenis</th>
                    <th style="width: 20%;">Jumlah Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk->jenisProduk as $index => $jenis)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jenis->nama }}</td>
                        <td>{{ $jenis->jumlah_produk }} unit</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">Riwayat Perubahan Stok</div>
    @if ($riwayatGrouped->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th>Jenis</th>
                    <th style="width: 12%;">Stok Awal</th>
                    <th style="width: 12%;">Stok Masuk</th>
                    <th style="width: 12%;">Stok Keluar</th>
                    <th style="width: 12%;">Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayatGrouped as $index => $riwayat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $riwayat["tanggal"]->format("d M Y") }}</td>
                        <td>{{ $riwayat["jenis"] }}</td>
                        <td style="text-align: center;">{{ $riwayat["stok_awal"] }}</td>
                        <td style="text-align: center; color: #16a34a;">
                            {{ $riwayat["stok_masuk"] > 0 ? "+" . $riwayat["stok_masuk"] : "-" }}
                        </td>
                        <td style="text-align: center; color: #dc2626;">
                            {{ $riwayat["stok_keluar"] > 0 ? "-" . $riwayat["stok_keluar"] : "-" }}
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $riwayat["stok_akhir"] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #666;">Belum ada riwayat perubahan stok</p>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date("d F Y H:i:s") }}</p>
        <p>&copy; {{ date("Y") }} Rumah Jahit Ria. All rights reserved.</p>
    </div>
</body>

</html>
