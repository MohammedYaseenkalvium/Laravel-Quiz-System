<?php

namespace App\QuestionTypes;

use InvalidArgumentException;

class QuestionTypeRegistry
{
    private array $types = [];

    public function register(QuestionTypeInterface $type): void
    {
        $this->types[$type->key()] = $type;
    }

    public function get(string $key): QuestionTypeInterface
    {
        if (!isset($this->types[$key])) {
            throw new InvalidArgumentException("Unknown question type: {$key}");
        }

        return $this->types[$key];
    }

    public function keys(): array
    {
        return array_keys($this->types);
    }

    public function all(): array
    {
        return $this->types;
    }
}