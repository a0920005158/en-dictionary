<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Authenticatable
{
    use HasFactory;

    public function search(){
        if($this->cant('create',$this)){
            return true;
        }else{
            return false;
        }
    }
}
