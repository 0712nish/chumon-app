<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChumonMeisai extends Model
{
    protected $table = 'chumonmeisai';

    public $incrementing = false;   // 複合キー想定
    public $timestamps = false;

    protected $fillable = [
        'barcode',
        'kihonno',
        'meisaino',
        'shohinno',
        'startdate',
        'suryo',
        'tanka',
        'hyojitanka',
        'tani',
        'min',
        'stock',
        'step',
        'urikatano',
    ];

    /** 商品 */
    public function shohin()
    {
        return $this->belongsTo(
            Shohin::class,
            'shohinno',
            'shohinno'
        );
    }

    public function uriage()
    {
        return $this->hasOne(Uriagedata::class, 'barcode', 'barcode');
    }

}

