<?php

namespace App\Core;

class Container
{
    private /*array*/ $services = [];

    public function set(string $id, callable $factory): void
    {
        $this->services[$id] = $factory;
    }

    public function get(string $id)
    {
        if (!isset($this->services[$id])) {
            throw new \Exception("Service {$id} introuvable.");
        }

        if (is_callable($this->services[$id])) {
            $this->services[$id] = $this->services[$id]($this);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}