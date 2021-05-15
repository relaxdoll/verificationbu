<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTrackTitle extends Model
{
    protected $fillable = [
        'image_index',
        'title',
        'report_id',
    ];
}
