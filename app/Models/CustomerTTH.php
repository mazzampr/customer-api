<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTth extends Model
{
    protected $table = 'customertth';
    protected $primaryKey = 'TTOTTPNo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'TTHNo', 'SalesID', 'TTOTTPNo', 'CustID', 'DocDate',
        'Received', 'ReceivedDate', 'FailedReason'
    ];

    protected $casts = [
        'DocDate' => 'date',
        'ReceivedDate' => 'datetime',
        'Received' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustID', 'CustID');
    }

    public function detail()
    {
        return $this->hasMany(CustomerTthDetail::class, 'TTOTTPNo', 'TTOTTPNo');
    }
}
