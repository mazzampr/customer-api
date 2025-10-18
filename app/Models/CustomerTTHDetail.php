<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTthDetail extends Model
{
    protected $table = 'customertthdetail';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TTHNo', 'TTOTTPNo', 'Jenis', 'Qty', 'Unit'
    ];
}
