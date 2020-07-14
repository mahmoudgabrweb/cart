<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ["order_id", "reference_type", "reference_value", "reference_discount", "total"];
}
