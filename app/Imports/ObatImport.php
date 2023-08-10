<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ObatImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Obat([
            'nama_obat' => $row['nama_obat'],
            'harga_jual' => $row['harga_jual'],
            'harga_beli' => $row['harga_beli'],
            'stok' => $row['stok'],
        ]);
    }
}
