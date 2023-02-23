<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'identity',
        'is_lock'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $userId = "-1";
    public $userData;

    function __construct(){
    }

    public function chgPassWord(string $password)
    {
        $this->userData = User::findByMid(1);
        if ($this->cant('isLock', $this)) {
            if ($this->userData->password != $password) {
                $this->userData->password = $password;
                $this->userData->save();
            }
        } else {
            return [];
        }
    }

    public static function findByMid($mid)
    {
        return User::where("id", "=", $mid)->get()->first();
    }

    public function search()
    {
        $auth = $this->cant('create', $this);
        if ($this->cant('userIdentity', $this)) {
            return true;
        } else {
            return false;
        }
    }
}
