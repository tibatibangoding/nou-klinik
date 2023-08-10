<?php

namespace App\Imports;

use App\Models\Tindakan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TindakanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Tindakan([
            'nama_tindakan' => $row['nama_tindakan'],
            'biaya_tindakan' => $row['biaya_tindakan'],
        ]);
    }
}
