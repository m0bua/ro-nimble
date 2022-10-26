<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\PaymentMethodTerms
 *
 * @property int $id
 * @property string $title
 * @property int $number_of_payments
 * @property int $number_of_month
 * @property float $min_goods_price_limit
 * @property float $max_goods_price_limit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 */
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
