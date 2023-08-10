<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    use HasFactory;

    protected $table = 'surgery';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the Surgery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
