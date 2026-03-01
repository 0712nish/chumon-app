<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uriagedata extends Model
{
    protected $table = 'uriagedata';
    public $timestamps = false;

    protected $fillable = [
        'hsid',
        'uridate',
        'barcode',
    ];
}

