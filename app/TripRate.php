<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripRate extends Model
{
    protected $fillable = [
        'route_id',
        'trip_rate_type_id',
        '10-11',
        '11-12',
        '12-13',
        '13-14',
        '14-15',
        '15-16',
        '16-17',
        '17-18',
        '18-19',
        '19-20',
        '20-21',
        '21-22',
        '22-23',
        '23-24',
        '24-25',
        '25-26',
        '26-27',
        '27-28',
        '28-29',
        '29-30',
    ];

    public function trip_rate_type()
    {
        return $this->belongsTo('App\TripRateType');
    }

    public function index()
    {

        return $this->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [
                $key => [
                    'route_id'          => $item->route_id,
                    'trip_rate_type_id' => $item->trip_rate_type_id,
                    'id'                => $item->id,
                    'trip_rate_type'    => $item->trip_rate_type->name,
                    'diesel_adjust'     => number_format($item->diesel_adjust,5),
                    '10-11'             => $item->{'10-11'} ? number_format($item->{'10-11'}, 5) : null,
                    '11-12'             => $item->{'11-12'} ? number_format($item->{'11-12'}, 5) : null,
                    '12-13'             => $item->{'12-13'} ? number_format($item->{'12-13'}, 5) : null,
                    '13-14'             => $item->{'13-14'} ? number_format($item->{'13-14'}, 5) : null,
                    '14-15'             => $item->{'14-15'} ? number_format($item->{'14-15'}, 5) : null,
                    '15-16'             => $item->{'15-16'} ? number_format($item->{'15-16'}, 5) : null,
                    '16-17'             => $item->{'16-17'} ? number_format($item->{'16-17'}, 5) : null,
                    '17-18'             => $item->{'17-18'} ? number_format($item->{'17-18'}, 5) : null,
                    '18-19'             => $item->{'18-19'} ? number_format($item->{'18-19'}, 5) : null,
                    '19-20'             => $item->{'19-20'} ? number_format($item->{'19-20'}, 5) : null,
                    '20-21'             => $item->{'20-21'} ? number_format($item->{'20-21'}, 5) : null,
                    '21-22'             => $item->{'21-22'} ? number_format($item->{'21-22'}, 5) : null,
                    '22-23'             => $item->{'22-23'} ? number_format($item->{'22-23'}, 5) : null,
                    '23-24'             => $item->{'23-24'} ? number_format($item->{'23-24'}, 5) : null,
                    '24-25'             => $item->{'24-25'} ? number_format($item->{'24-25'}, 5) : null,
                    '25-26'             => $item->{'25-26'} ? number_format($item->{'25-26'}, 5) : null,
                    '26-27'             => $item->{'26-27'} ? number_format($item->{'26-27'}, 5) : null,
                    '27-28'             => $item->{'27-28'} ? number_format($item->{'27-28'}, 5) : null,
                    '28-29'             => $item->{'28-29'} ? number_format($item->{'28-29'}, 5) : null,
                    '29-30'             => $item->{'29-30'} ? number_format($item->{'29-30'}, 5) : null,
                ]
            ];
        });
    }
}
