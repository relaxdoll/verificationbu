<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTrack extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_number',
        'type',
        'fleet_id',
        'is_active',
    ];

    protected $relationship = [
        'fleet',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    public function customers()
    {
        return $this->belongsToMany('App\Customer');
    }

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function image_title()
    {
        return $this->hasMany('App\ImageTrackTitle', 'report_id');
    }

    public function index()
    {

        return $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            $data = $item->original;

            $relations = $item->relations;

            foreach ($relations as $relationName => $relation)
            {
                $data[$relationName] = $relation->name;

            }

            return [$key => ['fleet'        => $item->fleet->name,
                             'fleet_id'     => $item->fleet_id,
                             'id'           => $item->id,
                             'title'        => $item->title,
                             'description'  => $item->description,
                             'image_number' => $item->image_number,
                             'type'         => function ($item) {
                                 switch ($item->type)
                                 {
                                     case 0:
                                         return 'ส่งอัตโนมัติ';
                                     case 1:
                                         return 'รออนุมัติ';
                                     default:
                                         return null;
                                 }
                             },
            ]];
        });
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
