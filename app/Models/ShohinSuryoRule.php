<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShohinSuryoRule extends Model
{
    protected $table = 'shohinsuryorule';

    public $timestamps = false;

    protected $fillable = [
        'autono',
        'shohinno',
        'suryo',
        'label',
        'sortno',
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
}
