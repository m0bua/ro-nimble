<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\PaymentParentMethodTranslation
 *
 * @property int $id
 * @property int $payment_parent_method_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read PaymentParentMethod $entity
 * @method static Builder|PaymentParentMethodTranslation newModelQuery()
 * @method static Builder|PaymentParentMethodTranslation newQuery()
 * @method static Builder|PaymentParentMethodTranslation query()
 * @method static Builder|PaymentParentMethodTranslation whereColumn($value)
 * @method static Builder|PaymentParentMethodTranslation whereCreatedAt($value)
 * @method static Builder|PaymentParentMethodTranslation whereId($value)
 * @method static Builder|PaymentParentMethodTranslation whereLang($value)
 * @method static Builder|PaymentParentMethodTranslation wherePaymentParentMethodId($value)
 * @method static Builder|PaymentParentMethodTranslation whereUpdatedAt($value)
 * @method static Builder|PaymentParentMethodTranslation whereValue($value)
 * @method static Builder|Translation withLang()
 * @mixin Eloquent
 */
class PaymentParentMethodTranslation extends Translation
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'lang',
        'column',
        'value',
    ];
}
