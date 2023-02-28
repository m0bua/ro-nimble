<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProducersAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'parent_id',
        'producer_id',
        'url',
        'width',
        'height',
        'variant',
        'group_name',
        'order',
        'is_deleted',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class)->withDefault();
    }
}
