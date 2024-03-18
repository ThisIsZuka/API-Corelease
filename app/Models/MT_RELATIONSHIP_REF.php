<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_RELATIONSHIP_REF extends Model
{
    protected $table = 'MT_RELATIONSHIP_REF';

    use HasFactory;

    protected $fillable = [
        'RELATION_REF_ID',
        'RELATION_REF_NAME',
    ];

}
