<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationReports extends Model
{
    use HasFactory;

    protected $table = 'investigation_reports';

    protected $guarded = ['id'];
}
