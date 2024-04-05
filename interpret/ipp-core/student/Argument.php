<?php

namespace IPP\Student;

use IPP\Student\Exceptions\XMLStructureException;

class Argument {
    private string $type;
    private string $value;
    private int $argOrder;

    private int $intval;
    private bool $boolval;
    private string $stringval;


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

    public function getIntValue(): int {
        return $this->intval;
    }

    public function getBoolValue(): bool {
        return $this->boolval;
    }

    public function getStringValue(): string {
        return $this->stringval;
    }

    public function validate(): void {
        // validate argument's type and value
        // convert int|bool|string from the value to variable of particular type

        if ($this->type === 'int') {
            if (!preg_match('/^[-+]?[0-9]+$/', $this->value)) {
                throw new XMLStructureException("Invalid int value");
            }
            $this->intval = intval($this->value);
        }
        else if ($this->type === 'bool') {
            if (!in_array($this->value, ['true', 'false'])) {
                throw new XMLStructureException("Invalid bool value");
            }
            $this->boolval = $this->value === 'true' ? true : false;
        }
        else if ($this->type === 'string') {
            if (!preg_match('/^([^#\\\\]|\\\\[0-9]{3})*$/', $this->value)) {
                throw new XMLStructureException("Invalid string value");
            }
            // has to transform it to string
            $this->stringval = $this->value;
        }
        else if ($this->type === 'nil') {
            if ($this->value !== 'nil') {
                throw new XMLStructureException("Invalid nil value");
            }
        }
        else if ($this->type === 'type') {
            if (!in_array($this->value, ['int', 'bool', 'string'])) {
                throw new XMLStructureException("Invalid type");
            }
        }
        else if ($this->type === 'label') {
            if (!preg_match('/^[_\-$&%*!?a-zA-Z][_\-$&%*!?a-zA-Z0-9]*$/', $this->value)) {
                throw new XMLStructureException("Invalid label name");
            }
        }
        else if ($this->type === 'var') {
            if (!preg_match('/^(LF|GF|TF)@[_\-$&%*!?a-zA-Z][_\-$&%*!?a-zA-Z0-9]*$/', $this->value)) {
                throw new XMLStructureException("Invalid variable name");
            }
        }
        else {
            throw new XMLStructureException("Invalid argument type");
        }
    }
}
