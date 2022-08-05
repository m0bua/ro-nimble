<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethodsTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'number_of_payments',
        'number_of_month',
        'min_goods_price_limit',
        'max_goods_price_limit',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, 'parent_id');
    }
}
