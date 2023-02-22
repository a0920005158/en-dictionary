<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class SearchPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function create()
    {
        echo "XXXX";
        return true;
    }
}
