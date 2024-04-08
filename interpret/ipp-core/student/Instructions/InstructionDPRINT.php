<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// DPRINT <symb>
class InstructionDPRINT extends Instruction {

    public function execute(): void {
        if ($this->arguments[0]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[0]->getVarName());
            switch ($var->getType()) {
                case 'int':
                    if (is_int($var->getValue())) {
                        $this->interpreter->errorWriter()->writeInt($var->getValue());
                    }
                    break;
                case 'bool':
                    if (is_bool($var->getValue())) {
                        $this->interpreter->errorWriter()->writeBool($var->getValue());
                    }
                    break;
                case 'string':
                    if (is_string($var->getValue())) {
                        $this->interpreter->errorWriter()->writeString($var->getValue());
                    }
                    break;
                case 'nil':
                    $this->interpreter->errorWriter()->writeString('');
                    break;
            }
        }
        else {
            switch ($this->arguments[0]->getType()) {
                case 'int':
                    $this->interpreter->errorWriter()->writeInt($this->arguments[0]->intValue());
                    break;
                case 'bool':
                    $this->interpreter->errorWriter()->writeBool($this->arguments[0]->boolValue());
                    break;
                case 'string':
                    $this->interpreter->errorWriter()->writeString($this->arguments[0]->stringValue());
                    break;
                case 'nil':
                    $this->interpreter->errorWriter()->writeString('');
                    break;
            }
        }
    }
}
