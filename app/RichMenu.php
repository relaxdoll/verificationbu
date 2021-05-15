<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RichMenu extends Model
{
    protected $fillable = [
        'size',
        'selected',
        'name',
        'chatBarText',
        'richMenuId',
        'link',
        'areas',
    ];

    public function index()
    {

        return $this->orderBy('name', 'ASC')->where('link', '!=', null)->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => ['size'        => $item->size,
                                             'id'          => $item->id,
                                             'selected'    => $item->select,
                                             'name'        => $item->name,
                                             'chatBarText' => $item->chatBarText,
                                             'richMenuId'  => $item->richMenuId,
                                             'link'        => $item->link,
                                             'areas'       => $item->area,
            ]];
        });
    }
}
