<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceApproval extends Model
{
    protected $fillable = [
        'request_date',
        'start_date',
        'end_date',
        'approve_date',
        'schedule_date',
        'symptom',
        'request_mileage',
        'start_mileage',
        'end_mileage',
        'vehicle_id',
        'requester_id',
        'approver_id',
        'status_id',
        'technician_id',
        'checker_id',
        'fleet_id',
        'start_image_array',
        'end_image_array',
    ];

    public function technician()
    {
        return $this->belongsTo('App\Technician');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function approver()
    {
        return $this->belongsTo('App\Approver');
    }

    public function requester()
    {
        return $this->belongsTo('App\Driver', 'requester_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function maintenance_detail()
    {
        return $this->hasMany('App\MaintenanceDetail');
    }

    public function index($where)
    {
        return $this->where([$where])->get()->map(function ($item) {
            return [
                'id'                => $item->id,
                'request_date'      => $item->request_date,
                'start_date'        => $item->start_date,
                'end_date'          => $item->end_date,
                'approve_date'      => $item->approve_date,
                'schedule_date'     => $item->schedule_date,
                'symptom'           => $item->symptom,
                'request_mileage'   => $item->request_mileage,
                'start_mileage'     => $item->start_mileage,
                'vehicle'           => $item->vehicle,
                'requester'         => $item->requester,
                'approver'          => $item->approver,
                'status'            => $item->status,
                'repaired_fleet_id' => $item->repaired_fleet_id,
                'detail'            => $item->maintenance_detail,
            ];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet_id'  => $item->fleet_id,
                    'id'        => $item->id,
                    'firstName' => $item->firstName,
                    'lastName'  => $item->lastName,
                    'phone'     => $item->phone,
                    'lineId'    => $item->lineId,
            ];
        })[0];
    }

}
