<?php

namespace App\Exports;

use App\Models\ForecastResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ForecastResultsExport implements FromCollection, WithHeadings
{
    protected ForecastResult $result;

    public function __construct(ForecastResult $result)
    {
        $this->result = $result;
    }

    public function collection(): Collection
    {
        return new Collection([
            ['Produk', $this->result->product->name],
            ['Kategori', $this->result->product->category],
            ['SKU', $this->result->product->sku ?? '-'],
            ['Metode', strtoupper($this->result->method_used)],
            ['Periode Forecast', $this->result->forecast_period],
            ['Forecast Qty', $this->result->forecast_qty],
            ['MAD', $this->result->mad],
            ['MAPE', $this->result->mape . '%'],
            ['Ukuran Window', $this->result->window_size],
            ['Diproses Pada', $this->result->created_at->format('Y-m-d H:i')],
        ]);
    }

    public function headings(): array
    {
        return ['Label', 'Nilai'];
    }
}
