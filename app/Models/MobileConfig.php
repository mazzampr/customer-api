<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileConfig extends Model
{
    protected $table = 'mobileconfig';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'BranchCode', 'Name', 'Description', 'Value'
    ];
}
