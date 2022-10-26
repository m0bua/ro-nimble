<?php

namespace App\Models\Eloquent;

use App\Http\Requests\Service\TaskRequest;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use LogicException;
use Throwable;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\ElasticIndexTask
 *
 * @property int $id
 * @property string $alias
 * @property string $old_index
 * @property string $new_index
 * @property string $state
 * @property string|null $exception_message
 * @property bool $is_deleted
 * @property bool $is_indexed
 * @property bool $drop_old_index
 * @property bool $update_aliases
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read bool $can_be_processed
 * @property-read string $target_model_name
 * @method static Builder|ElasticIndexTask canBeProcessed()
 * @method static Builder|ElasticIndexTask newModelQuery()
 * @method static Builder|ElasticIndexTask newQuery()
 * @method static Builder|ElasticIndexTask query()
 * @method static Builder|ElasticIndexTask whereAlias($value)
 * @method static Builder|ElasticIndexTask whereCreatedAt($value)
 * @method static Builder|ElasticIndexTask whereDropOldIndex($value)
 * @method static Builder|ElasticIndexTask whereExceptionMessage($value)
 * @method static Builder|ElasticIndexTask whereId($value)
 * @method static Builder|ElasticIndexTask whereIsDeleted($value)
 * @method static Builder|ElasticIndexTask whereIsIndexed($value)
 * @method static Builder|ElasticIndexTask whereNewIndex($value)
 * @method static Builder|ElasticIndexTask whereOldIndex($value)
 * @method static Builder|ElasticIndexTask whereState($value)
 * @method static Builder|ElasticIndexTask whereUpdateAliases($value)
 * @method static Builder|ElasticIndexTask whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ElasticIndexTask extends Model
{
    use HasFactory;

    public const STATE_BACKLOG = 'Backlog';
    public const STATE_TODO = 'To do';
    public const STATE_IN_PROGRESS = 'In progress';
    public const STATE_DONE = 'Done';
    public const STATE_ERROR = 'Error';
    public const STATES = [
        self::STATE_BACKLOG,
        self::STATE_TODO,
        self::STATE_IN_PROGRESS,
        self::STATE_DONE,
        self::STATE_ERROR,
    ];

    protected $appends = [
        'can_be_processed',
    ];

    protected $fillable = [
        'alias',
        'old_index',
        'new_index',
        'state',
        'exception_message',
        'is_deleted',
        'is_indexed',
        'drop_old_index',
        'update_aliases',
    ];

    /**
     * Mark task as indexed
     *
     * @return bool
     */
    public function markAsIndexed(): bool
    {
        if (!$this->exists) {
            throw new LogicException('Task does not exist.');
        }

        $this->is_indexed = 'true';
        return $this->save();
    }

    /**
     * Mark task in progress
     *
     * @return bool
     */
    public function setStateInProgress(): bool
    {
        if (!$this->exists) {
            throw new LogicException('Task does not exist.');
        }

        $this->state = static::STATE_IN_PROGRESS;
        return $this->save();
    }

    /**
     * Mark task in progress
     *
     * @return bool
     */
    public function setStateDone(): bool
    {
        if (!$this->exists) {
            throw new LogicException('Task does not exist.');
        }

        $this->state = static::STATE_DONE;
        return $this->save();
    }

    /**
     * Catch and report error
     *
     * @param Throwable $t
     * @return bool
     */
    public function catchError(Throwable $t): bool
    {
        if (!$this->exists) {
            throw new LogicException('Task does not exist.');
        }

        Log::channel('message-publisher')->error($t->getMessage(), [
            'file' => $t->getFile(),
            'line' => $t->getLine(),
        ]);

        $this->state = self::STATE_ERROR;
        $this->exception_message = "Message: {$t->getMessage()}; File: {$t->getFile()}; Line: {$t->getLine()}";

        return $this->save();
    }

    private static function prepareDataFromRequest(TaskRequest $request): array
    {
        $boolAttributes = [
            'is_deleted',
            'is_indexed',
            'drop_old_index',
            'update_aliases',
        ];
        $rawData = $request->only([
            'alias',
            'old_index',
            'new_index',
            'state',
            'exception_message',
            'is_deleted',
            'is_indexed',
            'drop_old_index',
            'update_aliases',
        ]);
        $data = [];

        foreach ($rawData as $key => $item) {
            if (in_array($key, $boolAttributes, true)) {
                $data[$key] = $item ? "true" : "false";
            } else {
                $data[$key] = $item;
            }
        }

        return $data;
    }

    public static function makeFromRequest(TaskRequest $request): ElasticIndexTask
    {
        return self::create(self::prepareDataFromRequest($request));
    }

    public function updateFromRequest(TaskRequest $request): bool
    {
        return $this->update(self::prepareDataFromRequest($request));
    }

    /**
     * Calculated field 'can_be_processed'
     *
     * @return bool
     * @noinspection PhpUnused
     */
    public function getCanBeProcessedAttribute(): bool
    {
        return $this->exists && $this->state === static::STATE_TODO && !$this->is_deleted && !$this->is_indexed;
    }

    /**
     * 'canBeProcessed' query scope
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeCanBeProcessed(Builder $builder): Builder
    {
        return $builder
            ->whereNotIn('state', [
                static::STATE_BACKLOG,
                static::STATE_ERROR,
                static::STATE_DONE,
            ])
            ->where([
                'is_deleted' => 'false',
                'is_indexed' => 'false',
            ]);
    }
}
