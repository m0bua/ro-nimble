<?php

namespace App\Macros;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class SelectNestedTranslation
{
    /**
     * Current builder instance
     *
     * @var EloquentBuilder
     */
    private EloquentBuilder $builder;

    /**
     * Joined Eloquent model's class for fetch translation
     *
     * @var string
     */
    private string $targetClass;

    /**
     * Joined Eloquent model class table alias
     *
     * @var string
     */
    private string $targetClassAlias;

    /**
     * Translatable target entity field
     *
     * @var string
     */
    private string $field;

    /**
     * Field alias
     *
     * @var string
     */
    private string $alias;

    /**
     * Target language
     *
     * @var string
     */
    private string $lang;

    /**
     * @param EloquentBuilder $builder
     * @param string $targetClass
     * @param string $field
     * @param string $alias
     * @param string $targetClassAlias
     * @param string|null $lang
     */
    public function __construct(
        EloquentBuilder $builder,
        string          $targetClass,
        string          $field,
        string          $alias = '',
        string          $targetClassAlias = '',
        string          $lang = ''
    )
    {
        $this->builder = $builder;
        $this->targetClass = $targetClass;
        $this->field = $field;
        $this->alias = $alias;
        $this->targetClassAlias = $targetClassAlias;
        $this->lang = $lang === '' ? App::getLocale() : $lang;
    }

    /**
     * Build query
     *
     * @return EloquentBuilder
     * @noinspection DuplicatedCode
     */
    public function build(): EloquentBuilder
    {
        $model = $this->makeModel($this->targetClass);
        $relation = $this->getTranslationsRelationFromModel($model);
        $targetClassTable = $this->targetClassAlias !== '' ? $this->targetClassAlias : $model->getTable();
        $translationsTable = $relation->getRelated()->getTable();
        $modelKey = $relation->getLocalKeyName();
        $foreignKey = $relation->getForeignKeyName();

        $joinedTableAlias = "{$translationsTable}_$this->field";
        $defaultLanguage = config('translatable.default_language');
        $alias = empty($this->alias) ? $this->field : $this->alias;

        return $this->builder
            ->addSelect(DB::raw("$joinedTableAlias.value as $alias"))
            ->leftJoinSub(
                DB::query()
                    ->select()
                    ->fromSub(
                        DB::query()
                            ->select(DB::raw("distinct on ($foreignKey, \"column\") $foreignKey, lang, value"))
                            ->from($translationsTable)
                            ->when(
                                $this->lang === $defaultLanguage,
                                fn(QueryBuilder $q) => $q->where('lang', $this->lang),
                                fn(QueryBuilder $q) => $q->whereIn('lang', [$this->lang, $defaultLanguage]),
                            )
                            ->where('column', $this->field)
                            ->orderByRaw("$foreignKey, \"column\", (lang = ?)::INT desc", [$this->lang]),
                        "sub_$joinedTableAlias"
                    )
                    ->orderByRaw('(lang = ?)::INT desc', [$this->lang]),
                $joinedTableAlias,
                "$targetClassTable.$modelKey",
                "$joinedTableAlias.$foreignKey"
            );
    }

    /**
     * Make model from target class
     *
     * @param string $class
     * @return Model
     */
    private function makeModel(string $class): Model
    {
        if (!class_exists($class)) {
            throw new BadMethodCallException("Class [$class] doesn't exist");
        }

        $model = new $class();
        if ($model instanceof Model) {
            return $model;
        }

        throw new BadMethodCallException("Class [$class] must be instance of " . Model::class);
    }

    /**
     * Get translation's relation from provided model
     *
     * @param Model $model
     * @return HasMany
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function getTranslationsRelationFromModel(Model $model): HasMany
    {
        if ($model->isRelation('translations')) {
            return $model->translations();
        }

        throw new BadMethodCallException("Model [$this->targetClass] doesn't support translations");
    }
}
