<?php

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\StringOperationException;
use IPP\Student\Instruction;

// SETCHAR <var> <symb1> <symb2>
class InstructionSETCHAR extends Instruction {

    public function execute(): void {
        $var = $this->exec->getVariable($this->arguments[0]->getVarName());
        if ($var->getType() !== 'string') {
            throw new OperandTypeException("Instruction SETCHAR expects string variable as first argument");
        }
        if (!is_string($var->getValue())) {
            throw new OperandTypeException("Instruction SETCHAR expects string variable as first argument");
        }
        if ($this->arguments[1]->getType() === 'var') {
            $op1_var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($op1_var->getType() !== 'int') {
                throw new OperandTypeException("Instruction SETCHAR expects integer as second argument");
            }
            if (!is_int($op1_var->getValue())) {
                throw new OperandTypeException("Instruction SETCHAR expects integer as second argument");
            }
            $op1 = $op1_var->getValue();
        } else {
            if ($this->arguments[1]->getType() !== 'int') {
                throw new OperandTypeException("Instruction SETCHAR expects integer as second argument");
            }
            $op1 = $this->arguments[1]->intValue();
        }
        if ($this->arguments[2]->getType() === 'var') {
            $op2_var = $this->exec->getVariable($this->arguments[2]->getVarName());
            if ($op2_var->getType() !== 'string') {
                throw new OperandTypeException("Instruction SETCHAR expects string as third argument");
            }
            if (!is_string($op2_var->getValue())) {
                throw new OperandTypeException("Instruction SETCHAR expects string as third argument");
            }
            $op2 = $op2_var->getValue();
        } else {
            if ($this->arguments[2]->getType() !== 'string') {
                throw new OperandTypeException("Instruction SETCHAR expects string as third argument");
            }
            $op2 = $this->arguments[2]->stringValue();
        }

        if ($op1 < 0 || $op1 >= strlen($var->getValue())) {
            throw new StringOperationException("Instruction SETCHAR index out of bounds");
        }
        if ($op2 === '') {
            throw new StringOperationException("Instruction SETCHAR expects non-empty string as third argument");
        }

        $result = substr_replace($var->getValue(), $op2, $op1, 1);
        $this->exec->setVariable($this->arguments[0]->getVarName(), new Argument("string", $result, 0));
    }
}   
