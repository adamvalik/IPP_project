<?php

namespace IPP\Student;

use IPP\Student\Exceptions\XMLStructureException;

class Argument {
    private string $type;
    private string $value;
    private int $argOrder;

    public function __construct(string $type, string $value, int $argOrder) {
        $this->type = $type;

        // nodeValue returns '' when empty, only string type can have empty value
        if ($value === '' && $type !== 'string') {
            throw new XMLStructureException("Argument value cannot be empty");
        }
        $this->value = $value;
        $this->argOrder = $argOrder;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getArgOrder(): int {
        return $this->argOrder;
    }

    public function intValue(): int {
        $editted_value = str_replace(['_', 'o', 'O'], '', $this->value);
        return intval($editted_value, 0); // base 0 means that intval will determine the base
    }

    public function boolValue(): bool {
        return $this->value === 'true';
    }

    public function stringValue(): string {
        // converts all escape sequences to their respective characters
        return preg_replace_callback('/\\\d{3}/', function ($matches) { return chr((int)$matches[1]); }, $this->value ) ?? '';
    }
}
