<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleInspectionList extends Model
{
    protected $fillable = [
        'name',
        'standard',
        'vehicle_type_id',
    ];

    public function vehicle_type()
    {
        return $this->belongsTo('App\VehicleType');
    }

    public function index()
    {

        return $this->get()->mapWithKeys(function ($item, $key) {

            return [
                $key => [
                    'vehicle_type'      => $item->vehicle_type,
                    'vehicle_type_name' => $item->vehicle_type->name,
                    'id'                => $item->id,
                    'name'              => $item->name,
                    'standard'          => $item->standard,
                ]
            ];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return [
                'vehicle_type'      => $item->vehicle_type,
                'vehicle_type_name' => $item->vehicle_type->name,
                'vehicle_type_id'   => $item->vehicle_type->id,
                'id'                => $item->id,
                'name'              => $item->name,
                'standard'          => $item->standard,
            ];
        })[0];
    }
}
