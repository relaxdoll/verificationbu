<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'fleet_id',
        'lineId',
        'avatar',
        'line_user_id',
    ];

    protected $relationship = [
        'fleet',
    ];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function line_user()
    {
        return $this->belongsTo('App\LineUser');
    }

    public function index()
    {

        return $this->orderBy('firstName', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => ['fleet'           => $item->fleet->name,
                                             'fleet_id'        => $item->fleet_id,
                                             'id'              => $item->id,
                                             'name'            => $item->firstName . ' ' . $item->lastName,
                                             'firstName'       => $item->firstName,
                                             'lastName'        => $item->lastName,
                                             'phone'           => $item->phone,
                                             'avatar'          => $item->avatar,
                                             'lineId'          => $item->lineId,
                                             'request_license' => Vehicle::where('fleet_id', $item->fleet_id)->has('hasRequest')->get()->pluck('license')->toArray(),
            ]];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet_id'   => $item->fleet_id,
                    'id'         => $item->id,
                    'firstName'  => $item->firstName,
                    'lastName'   => $item->lastName,
                    'phone'      => $item->phone,
                    'lineId'     => $item->lineId,
            ];
        })[0];
    }
}
