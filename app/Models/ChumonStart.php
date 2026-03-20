<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChumonStart extends Model
{
    protected $table = 'chumonstart';

    // 🔵 主キー指定
    protected $primaryKey = 'autono';

    // autono が AUTO_INCREMENT なら true のままでOK
    public $incrementing = true;

    // created_at / updated_at が無いなら
    public $timestamps = false;

    protected $fillable = [
        'shohinno',
        'startdate',
        'enddate',
        'shohinname2',
        'tanka',
        'hyojitanka',
        'tani',
        'chumontani',
        'suryoruleno',
        //'is_on_sale',
        'min',
        'stock',
        'step',
        'biko'
    ];

    protected $casts = [
        'startdate' => 'date',
        'enddate'  => 'date',
    ];

    /* リレーション */
    /*public function shohin()
    {
        return $this->belongsTo(Shohin::class, 'shohinno', 'shohinno');
    }*/

    public function suryoRules()
    {
        return $this->hasMany(
            ShohinSuryoRule::class,
            'ruleno',   // 子テーブル
            'suryoruleno'    // 親（chumonstart）
        )->orderBy('sortno');
    }

}
