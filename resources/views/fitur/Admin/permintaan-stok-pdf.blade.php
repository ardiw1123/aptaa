<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order - {{ $po->no_request }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1e40af; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { vertical-align: top; font-size: 13px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #f1f5f9; color: #475569; font-size: 11px; text-transform: uppercase; padding: 10px; border: 1px solid #e2e8f0; }
        .items-table td { padding: 10px; border: 1px solid #e2e8f0; font-size: 12px; }
        .footer { margin-top: 50px; }
        .signature-box { width: 200px; text-align: center; float: right; }
        .signature-space { height: 80px; }
        .status-badge { color: #16a34a; font-weight: bold; text-transform: uppercase; border: 1px solid #16a34a; padding: 5px; display: inline-block; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>APTAA - SISTEM INVENTORI</h1>
        <p style="margin: 5px 0; font-size: 12px;">Permintaan Stok Barang (Purchase Order)</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 60%;">
                <strong>Kepada:</strong><br>
                Supplier Utama APTAA<br>
                Di Tempat
            </td>
            <td>
                <strong>No. Request:</strong> {{ $po->no_request }}<br>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($po->tanggal_request)->format('d F Y') }}<br>
                <strong>Status:</strong> <span class="status-badge">{{ $po->status }}</span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th style="text-align: center;">Jumlah (Ekor)</th>
                <th style="text-align: center;">Jumlah (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->detailPermintaan as $index => $detail)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td style="text-align: center;">{{ number_format($detail->jumlah_unit, 0) }}</td>
                <td style="text-align: center;">{{ number_format($detail->jumlah_berat, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Catatan Admin:</strong> {{ $po->keterangan ?? '-' }}</p>

    <div class="footer">
        <div class="signature-box">
            <p style="font-size: 12px;">Disetujui Oleh,</p>
            <div class="signature-space"></div>
            <p style="font-size: 12px;"><strong>{{ $po->manajer->name ?? '..........................' }}</strong></p>
            <p style="font-size: 10px; color: #666;">(Manajer Operasional)</p>
        </div>
    </div>
</body>
</html>