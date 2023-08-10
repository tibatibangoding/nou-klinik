<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    use HasFactory;

    protected $table = 'periksa';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the Periksa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class, 'id_tindakan', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_dokter', 'id');
    }
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id');
    }
    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep', 'id');
    }
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id');
    }
}
