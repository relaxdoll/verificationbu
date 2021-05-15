<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryType extends Model
{
    protected $fillable = [
        'name',
        'category',
        'has_serial',
        'sellable',
        'quantable',
    ];

    public function index()
    {

        return $this->orderBy('name', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key =>
                                        [
                                            'id'         => $item->id,
                                            'name'       => $item->name,
                                            'category'   => $item->category,
                                            'has_serial' => $item->has_serial,
                                            'sellable'   => $item->sellable,
                                            'quantable'  => $item->quantable,
                                            'type'       => 'Types',
                                        ]];
        });
    }
}
