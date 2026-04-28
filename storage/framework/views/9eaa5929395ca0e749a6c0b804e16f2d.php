<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon Barang - <?php echo e($bonBarang->kode_bon); ?></title>
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
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 10px 0;
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
            width: 100px;
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
            text-align: center;
            width: 80px;
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
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Bon</button>
    
    <div class="container">
        <!-- Header -->
        <div class="header" style="display: flex; align-items: center; gap: 30px; margin-bottom: 20px;">
            <img src="<?php echo e(asset('logo/kemenprin.png')); ?>" alt="Logo KEMENPRIN" style="width: auto; height: 80px; object-contain; margin-right: 10px;">
            <div style="flex: 1; text-align: center;">
                <h1 style="margin: 0;">BALAI BESAR STANDARDISASI DAN PELAYANAN JASA INDUSTRI AGRO</h1>
                <p style="font-size: 11pt; margin: 5px 0;">Jl. Ir. H. Juanda No. 11, Bogor 16122</p>
                <h2 style="margin: 10px 0 0 0;">BON BARANG</h2>
            </div>
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Nomor</div>
                <div class="info-value"><?php echo e($bonBarang->kode_bon); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Unit Kerja</div>
                <div class="info-value"><?php echo e($bonBarang->divisi); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal</div>
                <div class="info-value"><?php echo e(isset($tanggalCetak) ? $tanggalCetak->format('d F Y') : ''); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Keperluan</div>
                <div class="info-value"><?php echo e($bonBarang->keterangan ?? '-'); ?></div>
            </div>
        </div>
        
        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Barang</th>
                    <th style="width: 80px;">Jumlah<br>Diminta</th>
                    <th style="width: 80px;">Jumlah<br>Disetujui</th>
                    <th style="width: 80px;">Satuan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $bonBarang->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="no"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($detail->barang->nama_barang); ?></td>
                        <td class="volume"><?php echo e($detail->jumlah_diminta); ?></td>
                        <td class="volume"><?php echo e($detail->jumlah_disetujui ?? 0); ?></td>
                        <td class="volume"><?php echo e($detail->barang->satuan); ?></td>
                        <td><?php echo e($detail->catatan ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">Mengetahui :</div>
            <div class="signature-section" style="margin-top: 0;">
                <div class="signature-box">
                    <div class="signature-title">DISETUJUI OLEH ATASAN</div>
                    <div class="signature-name">
                        <?php if($bonBarang->tanggal_atasan): ?>
                            <span style="font-size: 24px; font-weight: bold;">☑</span>
                        <?php else: ?>
                            <span style="font-size: 24px; font-weight: bold;">☐</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">DISETUJUI GUDANG</div>
                    <div class="signature-name">
                        <?php if($bonBarang->tanggal_gudang): ?>
                            <span style="font-size: 24px; font-weight: bold;">☑</span>
                        <?php else: ?>
                            <span style="font-size: 24px; font-weight: bold;">☐</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">PENERIMA BARANG</div>
                    <div class="signature-name">
                        <span style="font-size: 24px; font-weight: bold;">☑</span>
                    </div>
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
<?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/gudang/print/print.blade.php ENDPATH**/ ?>