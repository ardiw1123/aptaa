<?php

namespace App\Exports;

use App\Models\PermintaanStok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermintaanStokExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function collection() {
        return PermintaanStok::with('detailPermintaan.barang')->where('id', $this->id)->get();
    }

    public function headings(): array {
        return ["No Pesanan", "Tanggal", "Nama Barang", "Unit (Ekor)", "Berat (Kg)", "Status"];
    }

    public function map($po): array {
        $rows = [];
        foreach ($po->detailPermintaan as $detail) {
            $rows[] = [
                $po->no_request,
                $po->tanggal_request,
                $detail->barang->nama_barang,
                $detail->jumlah_unit,
                $detail->jumlah_berat,
                $po->status
            ];
        }
        return $rows;
    }
}