<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Forecast</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; }
        h1, h2, h3 { margin: 0; }
        .header { margin-bottom: 24px; }
        .card { margin-bottom: 16px; padding: 16px; border: 1px solid #e5e7eb; border-radius: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 8px 10px; border: 1px solid #d1d5db; }
        th { background-color: #f8fafc; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Forecast</h1>
        <p>Produk: {{ $result->product->name }} ({{ strtoupper($result->method_used) }})</p>
        <p>Periode Forecast: {{ $result->forecast_period }}</p>
    </div>

    <div class="card">
        <h2>Ringkasan Forecast</h2>
        <table>
            <tbody>
                <tr><th>Produk</th><td>{{ $result->product->name }}</td></tr>
                <tr><th>Kategori</th><td>{{ ucfirst($result->product->category) }}</td></tr>
                <tr><th>SKU</th><td>{{ $result->product->sku ?? '-' }}</td></tr>
                <tr><th>Metode</th><td>{{ strtoupper($result->method_used) }}</td></tr>
                <tr><th>Forecast Qty</th><td>{{ number_format($result->forecast_qty, 0, ',', '.') }} pcs</td></tr>
                <tr><th>MAD</th><td>{{ number_format($result->mad, 4, ',', '.') }}</td></tr>
                <tr><th>MAPE</th><td>{{ number_format($result->mape, 4, ',', '.') }}%</td></tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Data Historis Terakhir</h2>
        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Aktual Qty</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentData as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->period)->format('Y-m-d') }}</td>
                        <td>{{ number_format($item->actual_qty, 0, ',', '.') }}</td>
                        <td>{{ $item->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
