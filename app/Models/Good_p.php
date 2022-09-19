<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good_p extends Model
{
    protected $table = 'good_p';
    public $timestamps = false;
    protected $fillable = [
        'tab_name',
        'cat_name',
        'site_url',
        'name',
        'description',
        'price',
        'old_price',
        'image',
    ];
}
