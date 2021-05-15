<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name'];

    public function index()
    {

        return $this->orderBy('id', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return $this->result =
                [$key =>
                     [
                         'id'         => $item->id,
                         'name'       => $item->name,
                         'created_at' => Carbon::parse($item['created_at'])->diffForHumans(),
                         'brand'      => 'Brand'
                     ]];
        });
    }
}
