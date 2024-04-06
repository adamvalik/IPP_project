<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;

class Variable {
    private ?string $value;
    private string $type;
    private bool $isInitialized;

    public function __construct(string $type, string $value = null) {
        $this->type = $type;
        $this->value = $value;
        $this->isInitialized = ($value !== null);
    }

    public function setValue(string $value): void {
        $this->value = $value;
        $this->isInitialized = true;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function getType(): string {
        return $this->type;
    }

    public function isInitialized(): bool{
        return $this->isInitialized;
    }

    public function checkInitialized(): void {
        if (!$this->isInitialized) {
            throw new MissingValueException("Variable not initialized");
        }
    }
}
