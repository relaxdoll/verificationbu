<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WHTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'line_items',
        'bill_id',
        'vendor_id',
        'vendor_name',
        'total',
        'date',
        'due_date',
        'bill_number',
        'tax_type',
        'tax_detail',
        'tax_date',
        'user_id',
    ];

    public function index()
    {

        return $this->orderBy('due_date', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => [
                'no'          => $item->no,
                'id'          => $item->id,
                'line_items'  => json_decode($item->line_items),
                'bill_id'     => $item->bill_id,
                'vendor_id'   => $item->vendor_id,
                'vendor_name' => $item->vendor_name,
                'total'       => $item->total,
                'date'        => $item->date,
                'due_date'    => $item->due_date,
                'bill_number' => $item->bill_number,
                'tax_type'    => $item->tax_type,
                'tax_detail'  => $item->tax_detail,
                'tax_date'    => $item->tax_date,
                'user_id'     => $item->user_id,
            ]];
        });
    }
}
