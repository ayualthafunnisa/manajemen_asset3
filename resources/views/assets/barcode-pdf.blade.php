<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Barcode Asset</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #000;
            background: #fff;
        }

        .asset-card {
            padding: 24px 16px 20px;
            border-bottom: 1.5px solid #aaa;
            page-break-inside: avoid;
        }
        .asset-card:last-child { border-bottom: none; }

        .card-name {
            text-align: center;
            margin-bottom: 18px;
        }
        .card-name h2 {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid #000;
            display: inline-block;
            padding: 6px 20px;
        }
        .card-name .kode {
            font-size: 13px;
            color: #444;
            margin-top: 5px;
        }

        .qr-table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .qr-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 14px;
        }
        .qr-table td:first-child {
            border-right: 1px dashed #999;
        }

        .qr-label {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .qr-underline {
            width: 60px;
            height: 2px;
            background: #000;
            margin: 0 auto 10px;
        }

        .qr-img {
            width: 230px;
            height: 230px;
        }
    </style>
</head>
<body>

@foreach($assets as $asset)
<div class="asset-card">

    <div class="card-name">
        <h2>{{ $asset->nama_asset }}</h2>
        <div class="kode">({{ $asset->kode_asset }})</div>
    </div>

    <table class="qr-table">
        <tr>
            <td>
                <div class="qr-label">Detail Asset</div>
                <div class="qr-underline"></div>
                <img class="qr-img" src="{{ $asset->qr_detail_uri }}" alt="QR Detail">
            </td>
            <td>
                <div class="qr-label">Laporan Kerusakan</div>
                <div class="qr-underline"></div>
                <img class="qr-img" src="{{ $asset->qr_kerusakan_uri }}" alt="QR Kerusakan">
            </td>
        </tr>
    </table>

</div>
@endforeach

</body>
</html>