<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic_p extends Model
{
    protected $table = 'characteristic_p';
    public $timestamps = false;

    protected $fillable = [
        'good_p_id',
        'name',
        'value',
    ];
}
