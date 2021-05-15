<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FastTrackBackground extends Model
{
    protected $fillable = [
        'link',
        'isActive'
    ];

    public static function activateBackground($id)
    {
        FastTrackBackground::where('isActive', 1)->update(['isActive' => 0]);
        return FastTrackBackground::find($id)->update(['isActive' => 1]);
    }

    public static function getActiveBackground()
    {
        return FastTrackBackground::where('isActive', 1)->first()->link;
    }
}
