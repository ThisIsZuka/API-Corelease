<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SETUP_PRODUCT_CONDITION_DETAIL extends Model
{
    protected $table = 'SETUP_PRODUCT_CONDITION_DETAIL';

    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'PROD_DETAIL_ID',
        'HP_PRODUCT_ID',
        'DOWN_ID',
        'INTALLMENT_ID',
        'INTEREST',
        'COMMISSION',
        'EXTRA_1',
        'EXTRA_2',
        'EXTRA_3',
    ];
}
