<?php

namespace Uticlass;

use Closure;
use Throwable;

class TryDownloadMethod
{
    /**
     * @var Closure[] $methods
     */
    protected array $methods = [];


    public static function create(): TryDownloadMethod
    {
        return new TryDownloadMethod();
    }

    public function addMethod(Closure $closure): TryDownloadMethod
    {
        $this->methods[] = $closure;
        return $this;
    }

    /**
     * @return string
     */
    public function execute(): string
    {
        $result = '';

        foreach ($this->methods as $method) {
            try {
                $result = $method();
                if (!empty($result)) break;
            } catch (Throwable $exception) {
            }
        }

        return $result;
    }
}