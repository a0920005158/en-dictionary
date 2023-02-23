<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //身分驗證
    public function userIdentity(User $user){
        $data = $user->where("id",1)->first()->get();
        print_r($data);
        return true;
    }

    //身分驗證
    public function isLock(User $user){
        return $user->userData->is_lock;
    }
}
