<?php

namespace App\Models;

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
        'is_on_sale',
        'min',
        'stock',
        'step',
        'biko'
    ];

    public function chumonStarts()
    {
        return $this->hasMany(ChumonStart::class, 'shohinno', 'shohinno');
    }

}
