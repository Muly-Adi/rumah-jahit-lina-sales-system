<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
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

        .summary-boxes {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 0 5px;
            text-align: center;
        }

        .summary-box h3 {
            font-size: 20px;
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

        table.data-table tfoot td {
            background-color: #f3f4f6;
            font-weight: bold;
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
        }
    </style>
</head>

<body>
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <a href="{{ route("laporan.index") }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="header">
        <h1>Rumah Jahit Lina</h1>
        <p>Laporan Penjualan</p>
        <p>Periode: {{ date("d F Y", strtotime($tanggalAwal)) }} - {{ date("d F Y", strtotime($tanggalAkhir)) }}</p>
    </div>

    <div class="summary-boxes">
        <div class="summary-box">
            <p>Total Transaksi</p>
            <h3>{{ $totalTransaksi }}</h3>
            <p>Transaksi</p>
        </div>
        <div class="summary-box">
            <p>Total Pendapatan</p>
            <h3>Rp {{ number_format($totalPendapatan, 0, ",", ".") }}</h3>
            <p>Rupiah</p>
        </div>
        <div class="summary-box">
            <p>Total Modal</p>
            <h3>Rp {{ number_format($totalModal, 0, ",", ".") }}</h3>
            <p>Rupiah</p>
        </div>
        <div class="summary-box">
            <p>Total Keuntungan</p>
            <h3 style="color: #059669;">Rp {{ number_format($totalKeuntungan, 0, ",", ".") }}</h3>
            <p>Rupiah</p>
        </div>
    </div>

    <div class="section-title">Detail Transaksi</div>
    @if ($transaksis->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Kode Invoice</th>
                    <th>Customer</th>
                    <th style="width: 18%;">Total</th>
                    <th style="width: 15%;">Status Kirim</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaksi->tanggal->format("d M Y") }}</td>
                        <td>{{ $transaksi->kode_invoice }}</td>
                        <td>{{ $transaksi->nama }}</td>
                        <td>Rp {{ number_format($transaksi->total_bayar, 0, ",", ".") }}</td>
                        <td>{{ $transaksi->status_pengiriman ? "Sudah Dikirim" : "Belum Dikirim" }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL PENDAPATAN:</td>
                    <td colspan="2" style="font-weight: bold;">Rp {{ number_format($totalPendapatan, 0, ",", ".") }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL MODAL:</td>
                    <td colspan="2" style="font-weight: bold; color: #DC2626;">Rp {{ number_format($totalModal, 0, ",", ".") }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL KEUNTUNGAN:</td>
                    <td colspan="2" style="font-weight: bold; color: #059669;">Rp {{ number_format($totalKeuntungan, 0, ",", ".") }}</td>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #666;">Tidak ada transaksi pada periode ini</p>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date("d F Y H:i:s") }}</p>
        <p>&copy; {{ date("Y") }} Rumah Jahit Lina. All rights reserved.</p>
    </div>
</body>

</html>
