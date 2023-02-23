<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SearchPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function searchAuth(){

    }

    public function create()
    {
        echo "XXXX";
        return true;
    }
}
