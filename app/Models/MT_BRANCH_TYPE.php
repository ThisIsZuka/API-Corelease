<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_BRANCH_TYPE extends Model
{
    protected $table = 'MT_BRANCH_TYPE';

    use HasFactory;

    protected $fillable = [
        'BRANCH_TYPE_ID',
        'BRANCH_NAME',
        'ACTIVE_STATUS',
    ];

}
