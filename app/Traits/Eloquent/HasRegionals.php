<?php

namespace App\Traits\Eloquent;

use App\Casts\Regional;
use App\Helpers\CountryHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Trait HasRegionals
 *
 * Trait to make Eloquent models extra country-specific fields
 * First must be created a table for country-specific fields for each entity. For example
 *
 * Schema::create('category_regionals', function (Blueprint $table) {
 *      $table->id();
 *      $table->unsignedBigInteger('category_id');
 *      $table->string('country', 3);
 *      $table->string('column');
 *      $table->text('value');
 *      $table->timestamps();
 *      $table->unique([
 *          'category_id',
 *          'country',
 *          'column',
 *      ]);
 * });
 *
 * Must be created a model for country-specific fields in exactly the same namespace as the regional entity
 * Naming: Base model name + 'Regional' suffix
 * For example: App\Models\Eloquent\Category => App\Models\Eloquent\CategoryRegional
 * Also, you can define custom related model namespace by setting $regionalModelNamespace property
 * If the class does not exist, the relationship will not be created
 *
 * You can define all regional fields in $casts property of base model like this:
 * protected $casts = [
 *      'i_am_regional' => App\Casts\Regional::class
 * ];
 * So, you can get or set country-specific fields via magic properties:
 *
 * $model->i_am_regional = [
 *      'ua' => 'Україна',
 *      'pl' => 'Польша'
 * ];
 *
 * $model->i_am_regional will return active or default country
 *
 * Nota bene: All of these actions can be performed using public trait methods, without having to set up casts
 *
 * @package App\Traits\Eloquent
 */
trait HasRegionals
{
    /**
     * Name of cast
     *
     * @var string
     */
    public static string $regionalCast = Regional::class;

    /**
     * @return array<string>
     */
    public function getRegionalProperties(): array
    {
        return $this->getFillableProperties(self::$regionalCast);
    }

    /**
     * Defines base relationship with country-specific fields
     * You can define custom related model namespace by setting $regionalModelNamespace property
     *
     * @return HasMany
     */
    public function regionals(): HasMany
    {
        $model = $this->regionalModelNamespace ?? (static::class . 'Regional');

        if (!class_exists($model)) {
            throw new LogicException('Regional model not found');
        }

        return $this->hasMany($model);
    }

    /**
     * @comment WARNING! This scope must be in start of all query
     * @param Builder $builder
     * @return Builder
     */
    public function scopeLoadRegionals(Builder $builder): Builder
    {
        return $builder->with('regionals', function (HasMany $q) {
            $tableName = $this->regionals()->getRelated()->getTable();
            $country = CountryHelper::getRequestCountry();
            $defaultCountry = config('regional.country');

            return $q->select()
                ->fromSub(
                    DB::query()
                        ->select(DB::raw('distinct on ("column") *'))
                        ->from($tableName)
                        ->whereIn(Regional::FIELD, [$country, $defaultCountry])
                        ->orderByRaw('"column", (country = ?)::INT desc', [$country]),
                    $tableName
                )
                ->orderByRaw('(country = ?)::INT desc', [$country]);
        });
    }

    /**
     * Set or update Regional for single countryuage
     *
     * @param string $country Regional countryuage
     * @param string $column Regional column
     * @param string $value Regional
     * @return int
     */
    public function setRegional(string $country, string $column, string $value): int
    {
        return $this
            ->regionals()
            ->upsert(
                $this->prepareRegionalDataForSave($country, $column, $value),
                $this->getRegionalUniqueColumns(),
                $this->getRegionalUpdateColumns()
            );
    }

    /**
     * Set or update regionals for many countries
     *
     * @param string $column Regional column
     * @param array $values Associative array of values, country => value
     * @return int|null
     */
    public function setRegionals(string $column, array $values): ?int
    {
        if (empty($values)) {
            return null;
        }

        $data = [];

        foreach ($values as $country => $value) {
            $data[] = $this->prepareRegionalDataForSave($country, $column, $value);
        }

        return $this
            ->regionals()
            ->upsert(
                $data,
                $this->getRegionalUniqueColumns(),
                $this->getRegionalUpdateColumns()
            );
    }

    /**
     * Get country-specific fields for single country
     *
     * @param string $column Regional column
     * @param string $country Regional country
     * @return string|null
     */
    public function getRegional(string $column, string $country): ?string
    {
        if ($this->relationLoaded('regionals')) {
            $value = $this->regionals
                ->where('column', $column)
                ->where(Regional::FIELD, $country)
                ->first()->value ?? null;
        }

        return $value ?? $this
            ->regionals()
            ->where('column', $column)
            ->whereIn(Regional::FIELD, [$country, config('regional.default_country')])
            ->orderByRaw('(country = ?)::INT desc', [$country])
            ->value('value');
    }

    /**
     * Get country-specific fields for all countries
     *
     * @param string $column Regional column
     * @param array<string>|null $countries Selected countries. If null - all country-specific fields will be returned
     * @return array<string>
     */
    public function getRegionals(string $column, array $countries = null): array
    {
        return $this
            ->regionals()
            ->where('column', $column)
            ->when($countries, fn ($q) => $q->whereIn(Regional::FIELD, $countries))
            ->get()
            ->mapWithKeys(fn ($t) => [$t->country => $t->value])
            ->toArray();
    }

    /**
     * Check if country exists
     *
     * @param string $column Regional column
     * @param string $country country
     * @return bool
     */
    public function hasRegional(string $column, string $country): bool
    {
        return $this
            ->regionals()
            ->where('column', $column)
            ->where(Regional::FIELD, $country)
            ->exists();
    }

    /**
     * Get foreign key name for country-specific fields
     *
     * @return string
     */
    private function getRegionalForeignKey(): string
    {
        return $this->regionals()->getForeignKeyName();
    }

    /**
     * Unique columns for upsert
     *
     * @return array<string>
     */
    private function getRegionalUniqueColumns(): array
    {
        return [
            $this->getRegionalForeignKey(),
            Regional::FIELD,
            'column'
        ];
    }

    /**
     * Update columns for upsert
     *
     * @return array<string>
     */
    private function getRegionalUpdateColumns(): array
    {
        return [
            'value', 'need_delete'
        ];
    }

    /**
     * Prepare data for save
     *
     * @param string $country country
     * @param string $column Regional field key
     * @param string $value Regional field value
     * @return array
     */
    private function prepareRegionalDataForSave(string $country, string $column, string $value): array
    {
        return [
            $this->getRegionalForeignKey() => $this->id,
            Regional::FIELD => $country,
            'column' => $column,
            'value' => $value,
            'need_delete' => 0,
        ];
    }
}
