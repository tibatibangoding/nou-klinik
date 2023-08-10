<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pendaftaran;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the Pembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id');
    }
    public function apoteker()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
