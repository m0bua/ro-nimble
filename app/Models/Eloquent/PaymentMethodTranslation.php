<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\PaymentMethodTranslation
 *
 * @property int $id
 * @property int|null $payment_method_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read PaymentMethod $entity
 * @method static Builder|PaymentMethodTranslation newModelQuery()
 * @method static Builder|PaymentMethodTranslation newQuery()
 * @method static Builder|PaymentMethodTranslation query()
 * @method static Builder|PaymentMethodTranslation whereColumn($value)
 * @method static Builder|PaymentMethodTranslation whereCreatedAt($value)
 * @method static Builder|PaymentMethodTranslation whereId($value)
 * @method static Builder|PaymentMethodTranslation whereLang($value)
 * @method static Builder|PaymentMethodTranslation wherePaymentMethodId($value)
 * @method static Builder|PaymentMethodTranslation whereUpdatedAt($value)
 * @method static Builder|PaymentMethodTranslation whereValue($value)
 * @mixin Eloquent
 */
class PaymentMethodTranslation extends Translation
{
    protected $fillable = [
        'payment_method_id',
        'lang',
        'column',
        'value'
    ];
}
