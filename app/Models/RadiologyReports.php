<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyReports extends Model
{
    use HasFactory;

    protected $table = 'radiology_reports';

    protected $guarded = ['id'];
}
