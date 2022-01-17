<?php

namespace App\Traits\Eloquent;

use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Trait HasTranslations
 *
 * Trait to make Eloquent models translatable
 * First must be created a table for translations for each entity. For example
 *
 * Schema::create('category_translations', function (Blueprint $table) {
 *      $table->id();
 *      $table->unsignedBigInteger('category_id');
 *      $table->string('lang', 3);
 *      $table->string('column');
 *      $table->text('value');
 *      $table->timestamps();
 *      $table->unique([
 *          'category_id',
 *          'lang',
 *          'column',
 *      ]);
 * });
 *
 * Must be created a model for translation in exactly the same namespace as the translatable entity
 * Naming: Base model name + 'Translation' suffix
 * For example: App\Models\Eloquent\Category => App\Models\Eloquent\CategoryTranslation
 * Also, you can define custom related model namespace by setting $translationModelNamespace property
 * If the class does not exist, the relationship will not be created
 *
 * You can define all translatable fields in $casts property of base model like this:
 * protected $casts = [
 *      'i_am_translatable' => App\Casts\Translatable::class
 * ];
 * So, you can get or set translations via magic properties:
 *
 * $model->i_am_translatable = [
 *      'en' => 'in English',
 *      'ru' => 'на Русском'
 * ];
 *
 * $model->i_am_translatable will return active or default language translation
 *
 * Nota bene: All of these actions can be performed using public trait methods, without having to set up casts
 *
 * @package App\Traits\Eloquent
 */
trait HasTranslations
{
    /**
     * Name of cast
     *
     * @var string
     */
    public static string $translatableCast = Translatable::class;

    /**
     * Defines base relationship with translations
     * You can define custom related model namespace by setting $translationModelNamespace property
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        $model = $this->translationModelNamespace ?? (static::class . 'Translation');

        if (!class_exists($model)) {
            throw new LogicException('Translation model not found');
        }

        return $this->hasMany($model);
    }

    /**
     * @comment WARNING! This scope must be in start of all query
     * @param Builder $builder
     * @return Builder
     */
    public function scopeLoadTranslations(Builder $builder): Builder
    {
        return $builder->with('translations', function (HasMany $q) {
            $tableName = $this->translations()->getRelated()->getTable();
            $lang = App::getLocale();
            $defaultLang = config('translatable.default_language');

            return $q->select()
                ->fromSub(
                    DB::query()
                        ->select(DB::raw('distinct on ("column") *'))
                        ->from($tableName)
                        ->whereIn('lang', [$lang, $defaultLang])
                        ->orderByRaw('"column", (lang = ?)::INT desc', [$lang]),
                    $tableName
                )
                ->orderByRaw('(lang = ?)::INT desc', [$lang]);
        });
    }

    /**
     * Set or update translation for single language
     *
     * @param string $lang Translation language
     * @param string $column Translated column
     * @param string $value Translation
     * @return int
     */
    public function setTranslation(string $lang, string $column, string $value): int
    {
        return $this
            ->translations()
            ->upsert(
                $this->prepareDataForSave($lang, $column, $value),
                $this->getUniqueColumns(),
                $this->getUpdateColumns()
            );
    }

    /**
     * Set or update translations for many languages
     *
     * @param string $column Translated column
     * @param array $values Associative array of values, lang => value
     * @return int|null
     */
    public function setTranslations(string $column, array $values): ?int
    {
        if (empty($values)) {
            return null;
        }

        $data = [];

        foreach ($values as $lang => $value) {
            $data[] = $this->prepareDataForSave($lang, $column, $value);
        }

        return $this
            ->translations()
            ->upsert(
                $data,
                $this->getUniqueColumns(),
                $this->getUpdateColumns()
            );
    }

    /**
     * Get translation for single language
     *
     * @param string $column Translated column
     * @param string $lang Translation language
     * @return string|null
     */
    public function getTranslation(string $column, string $lang): ?string
    {
        if ($this->relationLoaded('translations')) {
            $value = $this->translations
                    ->where('column', $column)
                    ->where('lang', $lang)
                    ->first()->value ?? null;
        }

        return $value ?? $this
                ->translations()
                ->where('column', $column)
                ->whereIn('lang', [$lang, config('translatable.default_language')])
                ->orderByRaw('(lang = ?)::INT desc', [$lang])
                ->value('value');
    }

    /**
     * Get translations for all languages
     *
     * @param string $column Translated column
     * @param array<string>|null $languages Selected languages. If null - all translations will be returned
     * @return array<string>
     */
    public function getTranslations(string $column, array $languages = null): array
    {
        return $this
            ->translations()
            ->where('column', $column)
            ->when($languages, fn($q) => $q->whereIn('lang', $languages))
            ->get()
            ->mapWithKeys(fn($t) => [$t->lang => $t->value])
            ->toArray();
    }

    /**
     * Check if translation exists
     *
     * @param string $column Translated column
     * @param string $lang Translation language
     * @return bool
     */
    public function hasTranslation(string $column, string $lang): bool
    {
        return $this
            ->translations()
            ->where('column', $column)
            ->where('lang', $lang)
            ->exists();
    }

    /**
     * Get foreign key name for translations
     *
     * @return string
     */
    private function getTranslationForeignKey(): string
    {
        return $this->translations()->getForeignKeyName();
    }

    /**
     * Unique columns for upsert
     *
     * @return array<string>
     */
    private function getUniqueColumns(): array
    {
        return [
            $this->getTranslationForeignKey(),
            'lang',
            'column'
        ];
    }

    /**
     * Update columns for upsert
     *
     * @return array<string>
     */
    private function getUpdateColumns(): array
    {
        return [
            'value',
        ];
    }

    /**
     * Prepare data for save
     *
     * @param string $lang Translation language
     * @param string $column Translated column
     * @param string $value Translation
     * @return array
     */
    private function prepareDataForSave(string $lang, string $column, string $value): array
    {
        return [
            $this->getTranslationForeignKey() => $this->id,
            'lang' => $lang,
            'column' => $column,
            'value' => $value,
        ];
    }
}
