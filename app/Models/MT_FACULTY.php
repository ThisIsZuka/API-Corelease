<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_FACULTY extends Model
{
    protected $table = 'dbo.MT_FACULTY';

    use HasFactory;

    protected $fillable = [
        'MT_FACULTY_ID',
        'FACULTY_CODE',
        'FACULTY_NAME',
        'MT_CAMPUS_ID',
        'MT_UNIVERSITY_ID',
        'UNIVERSITY_CODE',
        'ACTIVE_STATUS',
    ];
}
