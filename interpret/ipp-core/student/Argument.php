<?php

namespace IPP\Student;

use IPP\Student\Exceptions\XMLStructureException;

class Argument {
    private string $type;
    private string $value;
    private int $argOrder;

    // private int $intval;
    // private bool $boolval;
    // private string $stringval;


    public function __construct(string $type, string $value, int $argOrder) {
        $this->type = $type;
        if ($value != '') {
            $this->value = $value;
        }
        else {
            throw new XMLStructureException("Argument value cannot be empty");
        }
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

    // public function getIntValue(): int {
    //     return $this->intval;
    // }

    // public function getBoolValue(): bool {
    //     return $this->boolval;
    // }

    // public function getStringValue(): string {
    //     return $this->stringval;
    // }

}
