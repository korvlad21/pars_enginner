<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files_p extends Model
{
    protected $table = 'files_p';
    public $timestamps = false;
    protected $fillable = [
        'good_p_id',
        'path',
    ];
}
