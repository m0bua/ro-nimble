<?php

namespace App\ValueObjects;

class Method
{
    public const GET = 'get';
    public const SET = 'set';

    /**
     * @var string
     */
    private string $methodPrefix;

    /**
     * @var string
     */
    private string $nameWithoutPrefix;

    /**
     * Property constructor.
     * @param string $methodName
     */
    public function __construct(string $methodName)
    {
        $this->cutIntoPieces($methodName);
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->methodPrefix;
    }

    /**
     * @return string
     */
    public function getNameWithoutPrefix(): string
    {
        return $this->nameWithoutPrefix;
    }

    /**
     * @return bool
     */
    public function isSet(): bool
    {
        return self::SET === $this->methodPrefix;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return self::GET === $this->methodPrefix;
    }

    /**
     * @param string $methodName
     */
    private function cutIntoPieces(string $methodName)
    {
        $pieces = explode('_', $methodName);
        $this->methodPrefix = array_shift($pieces);
        $this->nameWithoutPrefix = implode('_', $pieces);
    }
}
