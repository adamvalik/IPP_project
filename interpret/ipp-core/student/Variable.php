<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;

class Variable {
    private ?string $value;
    private ?string $type;
    private bool $isInitialized;

    public function __construct(string $type = null, string $value = null) {
        $this->type = $type;
        $this->value = $value;
        $this->isInitialized = ($value !== null && $type !== null);
    }

    public function setVariable(string $value, string $type): void {
        $this->value = $value;
        $this->type = $type;
        $this->isInitialized = true;
    }

    public function getValue(): ?string {
        $this->checkInitialized();
        return $this->value;
    }

    public function getType(): ?string {
        $this->checkInitialized();
        return $this->type;
    }

    public function checkInitialized(): void {
        if (!$this->isInitialized) {
            throw new MissingValueException("Variable not initialized");
        }
    }
}
