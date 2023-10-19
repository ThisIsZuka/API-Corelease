<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserAPI extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'API_Auth_User';

    protected $primaryKey = 'USER_ID';

    protected $fillable = [
        'USERNAME', 'PASSWORD', 'Auth_KEY'
    ];

    public $timestamps = true;

    const CREATED_AT = 'CREATE_AT';
    const UPDATED_AT = 'UPDATE_AT';

    function __construct()
    {
        date_default_timezone_set('Asia/bangkok');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'exp' => Carbon::now()->addDays(30)->timestamp,
            'user_id' => $this->getKey(),
            'username' => $this->USERNAME,
        ];
    }

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    public function getAuthIdentifierName()
    {
        return 'USERNAME';
    }
}
