<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChumonKihon extends Model
{
    protected $table = 'chumonkihon';
    protected $primaryKey = 'kihonno';

    public $incrementing = true;    // DB側AUTO_INCREMENT想定
    public $timestamps = false;

    protected $fillable = [
        'hsid',
        'urikatano',
        'status',
        'shoridate',
    ];

    /** 注文明細 */
    public function meisai()
    {
        return $this->hasMany(
            ChumonMeisai::class,
            'kihonno',
            'kihonno'
        );
    }
}
