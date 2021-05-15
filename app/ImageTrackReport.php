<?php

namespace App;

use App\Tool\Graph;
use Illuminate\Database\Eloquent\Model;

class ImageTrackReport extends Model
{
    protected $fillable = [

        'image_array',
        'note',
        'customer_id',
        'driver_id',
        'report_id',
        'vehicle_id',
        'vehicle_id2',
        'job_id',
        'status',
        'moved'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function report()
    {
        return $this->belongsTo('App\ImageTrack');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function graph()
    {
        $image_track_report = $this->all();

        return [
            'byWeek' => Graph::getGraphByWeek($image_track_report),
            'byDay' => Graph::getGraphByDay($image_track_report),
            'byMonth' => Graph::getGraphByMonth($image_track_report),
        ];
    }

    public static function getImageTrackDetail($item)
    {
        return [
            'customer'    => (!is_null($item->customer)) ? $item->customer->name : null,
            'vehicle'     => $item->vehicle->license,
            'image_array' => json_decode($item->image_array),
            'report'      => (!is_null($item->report)) ? $item->report->title : null,
            'note'        => $item->note,
            'created_at'  => $item->created_at,
            'raw'         => $item,
        ];
    }

}
