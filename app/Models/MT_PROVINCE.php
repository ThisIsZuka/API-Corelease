<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_PROVINCE extends Model
{
    protected $table = 'MT_PROVINCE';

    use HasFactory;

    protected $fillable = [
        'PROVINCE_ID',
        'PROVINCE_NAME'
    ];

    public function mt_district()
    {
        return $this->hasMany(MT_DISTRICT::class, 'PROVINCE_ID', 'PROVINCE_ID');
    }

}
