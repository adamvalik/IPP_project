<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// WRITE <symb>
class InstructionWRITE extends Instruction {

    public function execute(): void {
        if ($this->arguments[0]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[0]->getVarName());
            switch ($var->getType()) {
                case 'int':
                    if (is_int($var->getValue())) {
                        $this->interpreter->writer()->writeInt($var->getValue());
                    }
                    break;
                case 'bool':
                    if (is_bool($var->getValue())) {
                        $this->interpreter->writer()->writeBool($var->getValue());
                    }
                    break;
                case 'string':
                    if (is_string($var->getValue())) {
                        $this->interpreter->writer()->writeString($var->getValue());
                    }
                    break;
                case 'nil':
                    $this->interpreter->writer()->writeString('');
                    break;
            }
        }
        else {
            switch ($this->arguments[0]->getType()) {
                case 'int':
                    $this->interpreter->writer()->writeInt($this->arguments[0]->intValue());
                    break;
                case 'bool':
                    $this->interpreter->writer()->writeBool($this->arguments[0]->boolValue());
                    break;
                case 'string':
                    $this->interpreter->writer()->writeString($this->arguments[0]->stringValue());
                    break;
                case 'nil':
                    $this->interpreter->writer()->writeString('');
                    break;
            }
        }
    }
}
