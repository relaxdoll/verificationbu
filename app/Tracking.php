<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'bl_no',
        'booking_no',
        'container_no',
        'view',
        'slug',
    ];

    public function index()
    {

        return $this->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => [
                'bl_no'        => $item->bl_no,
                'booking_no'   => $item->booking_no,
                'container_no' => $item->container_no,
                'view'         => $item->view,
                'id'           => $item->id,
                'slug'           => $item->slug,
                'updated_at'   => ($item->created_at == $item->updated_at) ? '-' : $item->updated_at->shortRelativeToNowDiffForHumans(),
            ]];
        });
    }
}
