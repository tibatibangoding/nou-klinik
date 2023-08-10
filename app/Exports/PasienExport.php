<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function __construct($id_pasien)
    {
        $this->id_pasien = $id_pasien;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Periksa::query()->where('id_pasien', $this->id_pasien);
    }

    public function map($periksa): array
    {
        return [
            $periksa->id_pendaftaran,
            $periksa->pendaftaran->no_antrian,
            $periksa->resep->nama_resep,
            $periksa->pasien->nama_pasien,
            ($periksa->id_dokter == 0 ? 'Dr Rifat Sp Bs' : $periksa->user->nama),
            ($periksa->id_tindakan == 0 ? 'Tidak ada tindakan' : $periksa->tindakan->nama_tindakan),
            $periksa->detail_tindakan,
            $periksa->diagnosis,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Pendaftaran',
            'Nomor Antrian',
            'Nama Resep',
            'Nama Pasien',
            'Nama Dokter',
            'Jenis Tindakan',
            'Detail Tindakan',
            'Diagnosis Dokter',
        ];
    }
}
