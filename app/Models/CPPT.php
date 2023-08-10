<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPPT extends Model
{
    use HasFactory;

    protected $table = 'cppt';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the CPPT
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
