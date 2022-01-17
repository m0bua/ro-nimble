<?php

namespace App\Filters\Components\Options;

use App\Filters\Contracts\Filterable;
use App\Models\Eloquent\Option;
use Illuminate\Support\Collection;

abstract class AbstractOptionFilter implements Filterable
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $values;

    /**
     * Used for temporary hiding filter value
     *
     * @var bool
     */
    protected bool $isValueHidden = false;

    /**
     * Returns filter name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns filter values
     *
     * @return array
     */
    public function getValues(): Collection
    {
        return collect($this->isValueHidden ? [] : $this->values)->recursive();
    }

    /**
     * @return bool
     */
    public function toggleValues(): bool
    {
        $this->isValueHidden = !$this->isValueHidden;

        return $this->isValueHidden;
    }

    /**
     * Hide filter values
     */
    public function hideValues(): void
    {
        $this->isValueHidden = true;
    }

    /**
     * Show filter values
     */
    public function showValues(): void
    {
        $this->isValueHidden = false;
    }

    /**
     * @return bool
     */
    public function isValueHidden(): bool
    {
        return $this->isValueHidden;
    }

    /**
     * @return bool
     */
    public function issetFilter(): bool
    {
        return !!$this->getValues();
    }

    /**
     * @param $key
     * @return void
     */
    public function forgetValueItem($key)
    {
        unset($this->values[$key]);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function putValueItem($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * @param array $params
     * @return static
     */
    abstract public static function fromRequest(array $params): self;
}
