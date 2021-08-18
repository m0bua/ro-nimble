<?php

namespace App\Macros;

use App\Traits\Eloquent\HasTranslations;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SelectTranslation
{
    /**
     * Current builder instance
     *
     * @var EloquentBuilder
     */
    private EloquentBuilder $builder;

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
     * @param string $field
     * @param string $alias
     * @param string $lang if empty - will be used current language
     */
    public function __construct(EloquentBuilder $builder, string $field, string $alias = '', string $lang = '')
    {
        $this->builder = $builder;
        $this->field = $field;
        $this->alias = $alias;
        $this->lang = $lang === '' ? App::getLocale(): $lang;
    }

    /**
     * Build query
     *
     * @return EloquentBuilder
     * @noinspection DuplicatedCode
     */
    public function build(): EloquentBuilder
    {
        /** @var Model|HasTranslations $model */
        $model = $this->builder->getModel();
        $translationRelation = $model->translations();
        $joinedTable = $translationRelation->getRelated()->getTable();
        $foreignKey = $translationRelation->getForeignKeyName();
        $defaultLanguage = config('translatable.default_language');
        $table = $this->builder->toBase()->from;

        // If a table has an alias, we will refer to it by the alias
        if (Str::contains($table, ' as ')) {
            $table = Str::after($table, ' as ');
        }

        $joinedTableAlias = "{$joinedTable}_$this->field";
        $alias = empty($this->alias) ? $this->field : $this->alias;

        return $this->builder
            ->addSelect(DB::raw("$joinedTableAlias.value as $alias"))
            ->leftJoinSub(
                DB::query()
                    ->select()
                    ->fromSub(
                        DB::query()
                            ->select(DB::raw("distinct on ($foreignKey, \"column\") $foreignKey, lang, value"))
                            ->from($joinedTable)
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
                "$table.id",
                "$joinedTableAlias.$foreignKey"
            );
    }
}
