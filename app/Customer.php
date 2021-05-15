<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'nameTH',
        'email',
        'phone',
        'user_id',
        'lineId',
        'lineName',
        'isActive',
        'fleet_id',
        'line_group_id',
        'image_track_drive_id',
    ];

    protected $relationship = [
        'fleet',
        'user',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function lineGroup()
    {
        return $this->belongsTo('App\LineGroup');
    }

    public function reports()
    {
        return $this->belongsToMany('App\ImageTrack');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function image_track_drive()
    {
        return $this->belongsTo('App\GoogleDrive');
    }

    public static function getCustomerByFleetId($fleet_id)
    {
        return Customer::where('fleet_id', $fleet_id)->get()->toArray();
    }

    public function index()
    {

        return $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            return [$key => ['fleet'    => $item->fleet->name,
                             'fleet_id' => $item->fleet_id,
                             'id'       => $item->id,
                             'name'     => $item->name,
                             'nameTH'   => $item->nameTH,
                             'email'    => $item->email,
                             'phone'    => $item->phone,
                             'lineId'   => $item->lineId,
                             'lineName' => $item->lineName,
                             'isActive' => $item->isActive,
                             'line_group_id' => $item->line_group_id,
                             'line_group' => $item->lineGroup,
            ]];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet_id'   => $item->fleet_id,
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'nameTH'   => $item->nameTH,
                    'email'    => $item->email,
                    'phone'    => $item->phone,
                    'line_group_id' => $item->line_group_id,
            ];
        })[0];
    }

    public function setQueries()
    {
        $query = $this::query();

        $query->with($this->relationship)->where($this->whereData);

        foreach ($this->orderByData as $orderByDatum)
        {
            $query->orderBy($orderByDatum[0], $orderByDatum[1]);
        }


        return $query;
    }
}
