<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordDictionary extends Model
{
    use HasFactory;

    protected $table = 'word-dictionary';
    protected $primaryKey = 'id';

    protected $fillable = [
        'en',
        'cn',
        'example'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function searchWord($en){
        return WordDictionary::where("en",$en)->first();
    }
}
