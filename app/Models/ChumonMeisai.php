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
        'shohinname2',
        'suryo',
        'tanka',
        'hyojitanka',
        'tani',
        'suryoruleno',
        'min',
        'step',
        'biko',
        'urikatano',
    ];

    /** 商品 */
    /*public function shohin()
    {
        return $this->belongsTo(
            Shohin::class,
            'shohinno',
            'shohinno'
        );
    }*/

    public function uriage()
    {
        return $this->hasOne(Uriagedata::class, 'barcode', 'barcode');
    }

    public function start()
    {
        return $this->hasOne(
            ChumonStart::class,
            'shohinno',
            'shohinno'
        )->whereColumn(
            'chumonstart.startdate',
            'chumonmeisai.startdate'
        );
    }

    public function suryoRules()
    {
        return $this->hasMany(
            ShohinSuryoRule::class,
            'ruleno',   // 子テーブル
            'suryoruleno'    // 親（chumonmeisai）
        )->orderBy('sortno');
    }

}

