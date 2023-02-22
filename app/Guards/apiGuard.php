<?php

namespace App\Guards;

use App\Lib\iRandom;
use App\Lib\iServer;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Redis;

class apiGuard implements Guard
{
    protected $request, $userData;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function user()
    {
        return true;
    }

    public function check()
    {
        return true;
    }

    public function guest()
    {
        return false;
    }

    public function id()
    {
        return $this->userData["id"]; // mid
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser(Authenticatable $user)
    {
    }

}
