<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_LEVEL_TYPE extends Model
{
    protected $table = 'MT_LEVEL_TYPE';

    use HasFactory;

    protected $fillable = [
        'LEVEL_TYPE_ID',
        'LEVEL_TYPE',
    ];

}
