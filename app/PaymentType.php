<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = [
        'name',
        'is_monthly',
        'is_annually',
        'warning',
    ];
}
