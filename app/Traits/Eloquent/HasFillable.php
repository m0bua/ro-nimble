<?php

namespace App\Traits\Eloquent;

use App\Casts\Translatable;
use App\Casts\Regional;

trait HasFillable
{
    private static array $fillableTypes = [
        Translatable::class,
        Regional::class,
    ];

    /**
     * Returns all fillable attributes from Model
     *
     * @return array
     */
    public function getFillable(): array
    {
        $fillableCasts = $this->getFillableProperties();
        return array_filter($this->fillable, fn ($key) => !in_array($key, $fillableCasts, true));
    }

    /**
     * @return array<string>
     */
    public function getFillableProperties(?string $type = null): array
    {
        if (!empty($type)) {
            return array_keys($this->casts, $type);
        }

        $result = [];
        foreach (self::$fillableTypes as $type) {
            $result = array_merge($result, $this->getFillableProperties($type));
        }

        return array_unique($result);
    }

    /**
     * @param string $column
     * @return string
     */
    public function getFieldByColumn(string $column): string
    {
        return $this->casts[$column]::FIELD ?? '';
    }

    public function setFillable(string $lang, string $column, string $value): int
    {
        switch ($this->casts[$column]) {
            case (Translatable::class):
                return $this->setTranslation($lang, $column, $value);
            case (Regional::class):
                return $this->setRegional($lang, $column, $value);
        }

        return 0;
    }

    public function setFillables(string $column, array $values): int
    {
        switch ($this->casts[$column]) {
            case (Translatable::class):
                return $this->setTranslations($column, $values);
            case (Regional::class):
                return $this->setRegionals($column, $values);
        }

        return 0;
    }

    public function fillableModelCreate(string $column, array $data)
    {
        switch ($this->casts[$column]) {
            case (Translatable::class):
                $relation = $this->translatable()->getRelated();
                break;
            case (Regional::class):
                $relation = $this->regional()->getRelated();
                break;
        }

        if (isset($relation)) {
            return $relation->create($data);
        }

        return null;
    }
}
