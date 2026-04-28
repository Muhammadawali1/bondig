<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Bon Masuk - {{ $bonMasuk->supplier ?? 'Tanpa Supplier' }}</title>
    <style>
        @media print {
            @page {
                margin: 10mm;
                size: A4;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        
        .info-value {
            flex: 1;
            border-bottom: 1px solid #000;
            padding-left: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        table td.no {
            text-align: center;
            width: 30px;
        }
        
        table td.volume {
            text-align: right;
            width: 100px;
        }
        
        table td.price {
            text-align: right;
            width: 120px;
        }
        
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .total-box {
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .total-label {
            font-weight: bold;
        }
        
        .total-value {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-left: 5px;
            min-width: 150px;
            text-align: right;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
        }
        
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Faktur</button>
    
    <div class="container">
        <!-- Header with Supplier Name -->
        <div class="header">
            <h1>{{ $bonMasuk->supplier ?? 'Tanpa Supplier' }}</h1>
            <h2>FAKTUR BARANG MASUK</h2>
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Tanggal Masuk</div>
                <div class="info-value">{{ $bonMasuk->tanggal_masuk ? $bonMasuk->tanggal_masuk->format('d F Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Faktur</div>
                <div class="info-value">{{ $bonMasuk->tanggal_faktur ? $bonMasuk->tanggal_faktur->format('d F Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Petugas Gudang</div>
                <div class="info-value">{{ $bonMasuk->gudang->name }}</div>
            </div>
        </div>
        
        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Barang</th>
                    <th style="width: 80px;">Jumlah</th>
                    <th style="width: 80px;">Satuan</th>
                    <th style="width: 120px;">Harga Satuan</th>
                    <th style="width: 120px;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                @endphp
                @foreach($bonMasuk->details as $index => $detail)
                    @php
                        $totalHarga = $detail->harga_satuan ? ($detail->harga_satuan * $detail->jumlah_masuk) : 0;
                        $grandTotal += $totalHarga;
                    @endphp
                    <tr>
                        <td class="no">{{ $index + 1 }}</td>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td class="volume">{{ $detail->jumlah_masuk }}</td>
                        <td>{{ $detail->barang->satuan }}</td>
                        <td class="price">{{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '-' }}</td>
                        <td class="price">{{ $totalHarga > 0 ? 'Rp ' . number_format($totalHarga, 0, ',', '.') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Total Section -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <div class="total-label">Grand Total:</div>
                    <div class="total-value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        
    </div>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>
