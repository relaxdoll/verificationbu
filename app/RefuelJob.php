<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefuelJob extends Model
{
    protected $fillable = [
        'refuel_id',
        'sent',
        'model'
    ];

    protected $result = null;

    public function refuel()
    {
        return $this->belongsTo('App\Refuel');
    }

    public function index()
    {
        function getRefuelGroupName($fleet_id)
        {
            switch ($fleet_id)
            {
                case 1:
                case 4:
                    return LineGroup::find(8)->name;
                case 2:
                    return LineGroup::find(7)->name;
                case 3:
                    return LineGroup::find(9)->name;
            }
        }

        function getDriverGroupName($fleet_id)
        {
            switch ($fleet_id)
            {
                case 0:
                    return LineGroup::find(55)->name;
                case 1:
                    return LineGroup::find(4)->name;
                case 2:
                    return LineGroup::find(2)->name;
                case 3:
                    return LineGroup::find(5)->name;
                case 4:
                    return LineGroup::find(58)->name;
                case 5:
                    return LineGroup::find(60)->name;
                case 6:
                    return LineGroup::find(55)->name;
            }
        }

        return $this->where('sent', 0)->get()->mapWithKeys(function ($item, $key) {

            switch ($item->model)
            {
                case 'refuel':
                    $refuel = $item->refuel;

                    return $this->result = [$key => ['refuel' => $item->refuel,
                                                     'id'     => $item->refuel_id,
                                                     'job_id' => $item->id,
                                                     'group'  => getRefuelGroupName($refuel->fleet_id),
                                                     'model'  => 'refuel',
                    ]];

                case 'pressure':
                    return $this->result = [$key => ['job_id' => $item->id,
                                                     'id'     => $item->refuel_id,
                                                     'group'  => getDriverGroupName($item->refuel_id),
                                                     'model'  => 'pressure',
                    ]];

                case 'pressure_weekly_report':
                    return $this->result = [$key => ['job_id' => $item->id,
                                                     'id'     => $item->refuel_id,
                                                     'group'  => getDriverGroupName($item->refuel_id),
                                                     'model'  => 'pressure_weekly_report',
                    ]];

                case 'maintenance_approval_driver':
                    $data = MaintenanceApproval::find($item->refuel_id);

                    return $this->result = [$key => ['job_id' => $item->id,
                                                     'id'     => $item->refuel_id,
                                                     'group'  => getDriverGroupName(Driver::find($data->requester_id)->first()->fleet_id),
                                                     'model'  => 'maintenance_approval_driver',
                    ]];

                case 'maintenance_approval_approver':
                    return $this->result = [$key => ['job_id' => $item->id,
                                                     'id'     => $item->refuel_id,
                                                     'group'  => LineGroup::find(59)->name,
                                                     'model'  => 'maintenance_approval_approver',
                    ]];

                default:
                    break;
            }

        });
    }
}
