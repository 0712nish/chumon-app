<?php

namespace App\Models;

use App\Models\ChumonStart;
use App\Models\ShohinSuryoRule;
use Illuminate\Database\Eloquent\Model;

class Shohin extends Model
{
    protected $table = 'shohinmst';
    protected $primaryKey = 'shohinno';

    public $incrementing = false;   // 数値でも手動採番なら false
    public $timestamps = false;

    protected $fillable = [
        'shohinno',
        'shohinname2',
        'tanka',
        'hyojitanka',
        'tani',
        'chumontani',
        //'is_on_sale',
        'min',
        'stock',
        'step',
        'biko'
    ];

    public function chumonStarts()
    {
        return $this->hasMany(ChumonStart::class, 'shohinno', 'shohinno');
    }

    public function suryoRules()
    {
        return $this->hasMany(ShohinSuryoRule::class,'shohinno','shohinno')
            ->orderBy('sortno');
    }

}
