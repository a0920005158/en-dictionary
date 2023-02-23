<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Authenticatable
{
    use HasFactory;

    public $id = "123456";
    public function search(){
        if($this->cant('create',$this)){
            return true;
        }else{
            return false;
        }
    }
}
