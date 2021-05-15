<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleDrive extends Model
{
    protected $fillable = [
        'name',
        'folder_id'
    ];
}
