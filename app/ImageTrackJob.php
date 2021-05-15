<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTrackJob extends Model
{
    protected $fillable = [
        'image_track_report_id',
        'sent'
    ];

    protected $result = null;

    public function image_track_report()
    {
        return $this->belongsTo('App\ImageTrackReport');
    }

    public function index()
    {

        return $this->where('sent', 0)->get()->mapWithKeys(function ($item, $key) {

            $image_track_report = $item->image_track_report;

            return $this->result = [$key => [
                                             'id'     => $item->image_track_report_id,
                                             'job_id' => $item->id,
                                             'customer' => $image_track_report->customer->lineName,
                                             'group'  => LineGroup::find(54)->name,
            ]];
        });
    }
}
