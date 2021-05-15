<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'event',
        'place',
        'carrier'
    ];

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return [
                'id'      => $item->id,
                'date'    => $item->date,
                'event'   => $item->event,
                'place'   => $item->place,
                'carrier' => $item->carrier,
            ];
        })[0];
    }
}
