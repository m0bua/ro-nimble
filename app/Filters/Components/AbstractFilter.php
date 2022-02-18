<?php

namespace App\Filters\Components;

use App\Filters\Contracts\Filterable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

abstract class AbstractFilter implements Filterable
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
     * @return Collection
     */
    public function getValues(): Collection
    {
        return collect($this->isValueHidden ? [] : $this->values)->recursive();
    }

    /**
     * @return bool
     * @noinspection PhpUnused
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
     * @noinspection PhpUnused
     */
    public function isValueHidden(): bool
    {
        return $this->isValueHidden;
    }

    /**
     * @return bool
     * @noinspection PhpUnused
     */
    public function issetFilter(): bool
    {
        return $this->getValues()->isNotEmpty();
    }

    /**
     * @param $key
     * @return void
     * @noinspection PhpUnused
     */
    public function forgetValueItem($key): void
    {
        unset($this->values[$key]);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     * @noinspection PhpUnused
     */
    public function putValueItem($key, $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * @param FormRequest $request
     * @return $this
     */
    abstract public static function fromRequest(FormRequest $request): self;
}
