<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the DetailPenjualan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id');
    }
}
