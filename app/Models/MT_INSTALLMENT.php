<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MT_INSTALLMENT extends Model
{
    protected $table = 'dbo.MT_INSTALLMENT';

    use HasFactory;

    protected $fillable = [
        'INSTALL_ID',
        'INSTALL',
    ];

    public static function getInstallmentsByPrice($sumPrice)
    {
        return self::when($sumPrice >= 30000, function ($query) {
            return $query->whereIn('INSTALL_ID', [5, 7]);
        })
            ->when($sumPrice < 30000, function ($query) {
                return $query->whereIn('INSTALL_ID', [3, 4, 5, 7]);
            })
            ->get();
    }
}
