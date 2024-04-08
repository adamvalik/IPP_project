<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\StringOperationException;

// STRI2INT <var> <symb1> <symb2>
class InstructionSTRI2INT extends Instruction {

    public function execute(): void {
        $op1 = '';
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'string') {
                throw new OperandTypeException("Instruction STRI2INT expects string as second argument");
            }
            $op1 = $var->getValue();
        } else {
            if ($this->arguments[1]->getType() !== 'string') {
                throw new OperandTypeException("Instruction STRI2INT expects string as second argument");
            }
            $op1 = $this->arguments[1]->stringValue();
        }

        $op2 = 0;
        if ($this->arguments[2]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'int') {
                throw new OperandTypeException("Instruction STRI2INT expects integer as third argument");
            }
            $op2 = $var->getValue();
        } else {
            if ($this->arguments[2]->getType() !== 'int') {
                throw new OperandTypeException("Instruction STRI2INT expects integer as third argument");
            }
            $op2 = $this->arguments[2]->intValue();
        }

        if (!is_string($op1)) {
            throw new OperandTypeException("Instruction STRI2INT expects string as second argument");
        }
        if (!is_int($op2)) {
            throw new OperandTypeException("Instruction STRI2INT expects integer as third argument");
        }
        if ($op2 < 0 || $op2 >= strlen($op1)) {
            throw new StringOperationException("Instruction STRI2INT index out of bounds");
        }
        $char = $op1[$op2];
        /** @var int|false $result */
        $result = mb_ord($char, 'UTF-8');
        if ($result === false) {
            throw new StringOperationException("Instruction STRI2INT failed to convert character to integer");
        }

        $arg = new Argument("int", (string)$result, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
