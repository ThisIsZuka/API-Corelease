<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_STATUS extends Model
{
    protected $table = 'MT_STATUS';

    use HasFactory;

    protected $fillable = [
        'HP_STA_ID',
        'STA_NAME',
    ];

}
