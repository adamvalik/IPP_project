<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;

class Variable {
    private ?string $type;
    private mixed $value;
    private bool $isInitialized;

    public function __construct(string $type = null, mixed $value = null) {
        $this->type = $type;
        $this->value = $value;
        $this->isInitialized = ($value !== null && $type !== null);
    }

    public function setVariable(string $type, mixed $value): void {
        $this->type = $type;
        $this->value = $value;
        $this->isInitialized = true;
    }

    public function getValue(): mixed {
        $this->checkInitialized();
        return $this->value;
    }

    public function getType(): string {
        $this->checkInitialized();
        if ($this->type === null) {
            throw new MissingValueException("This should never happen because I've already checked if the variable is initialized.");
        }
        return $this->type;
    }

    public function checkInitialized(): void {
        if (!$this->isInitialized) {
            throw new MissingValueException("Variable not initialized");
        }
    }

    public function isInitialized(): bool {
        return $this->isInitialized;
    }
}
