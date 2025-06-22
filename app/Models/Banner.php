<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'section_name',
        'image_path',
        'link',
        'position',
    ];
}
