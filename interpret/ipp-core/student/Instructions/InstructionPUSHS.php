<?php

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Instruction;

// PUSHS <symb>
class InstructionPUSHS extends Instruction {

    public function execute(): void {
        if ($this->arguments[0]->getType() === 'var') {
            // if the symb is a variable, push its value to the stack (has to be string)
            $var = $this->exec->getVariable($this->arguments[0]->getVarName());
            $value = '';
            if ($var->getType() === 'nil') {
                $value = 'nil';
            }
            else if ($var->getType() === 'int') {
                if (is_int($var->getValue())) {
                    $value = (string)$var->getValue();
                }
            }
            else if ($var->getType() === 'bool') {
                $value = $var->getValue() ? 'true' : 'false';
            }
            else if ($var->getType() === 'string') {
                if (is_string($var->getValue())) {
                    $value = $var->getValue();
                }
            }
            else {
                throw new SemanticException("Invalid variable type");
            }
            $this->runEnv->pushData(new Argument($var->getType(), $value, 0));
        } else {
            $this->runEnv->pushData($this->arguments[0]);
        }
    }
}
