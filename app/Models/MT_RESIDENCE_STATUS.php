<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_RESIDENCE_STATUS extends Model
{
    protected $table = 'MT_RESIDENCE_STATUS';

    use HasFactory;

    protected $fillable = [
        'RESIDENCE_ID',
        'RESIDENCE_NAME',
    ];

}
