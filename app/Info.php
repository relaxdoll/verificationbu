<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'lat',
        'lng',
        'eta',
        'depart',
        'from',
        'to',
        'is_active',
    ];

    public static function activate($id)
    {
        Info::where('is_active', 1)->update(['is_active' => 0]);

        return Info::find($id)->update(['is_active' => 1]);
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['lat'    => $item->lat,
                    'id'     => $item->id,
                    'lng'    => $item->lng,
                    'eta'    => $item->eta,
                    'depart' => $item->depart,
                    'from'   => $item->from,
                    'to'     => $item->to,
            ];
        })[0];
    }
}
