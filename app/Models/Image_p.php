<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_p extends Model
{
    protected $table = 'image_p';

    protected $fillable = [
        'good_p_id',
        'path',
    ];
}
