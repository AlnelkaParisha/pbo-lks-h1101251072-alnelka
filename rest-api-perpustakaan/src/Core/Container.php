<?php

namespace App\Core;

use RuntimeException;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $abstract): mixed
    {
        if (!isset($this->bindings[$abstract])) {
            throw new RuntimeException(
                "Binding untuk {$abstract} tidak ditemukan"
            );
        }

        return $this->bindings[$abstract]($this);
    }
}