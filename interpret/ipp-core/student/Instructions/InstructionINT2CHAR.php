<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;
use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\StringOperationException;

// INT2CHAR <var> <symb>
class InstructionINT2CHAR extends Instruction {

    public function execute(): void {
        $op = 0;
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'int') {
                throw new OperandTypeException("Instruction INT2CHAR expects integer argument");
            }
            $op = $var->getValue();
        } else {
            if ($this->arguments[1]->getType() !== 'int') {
                throw new OperandTypeException("Instruction INT2CHAR expects integer argument");
            }
            $op = $this->arguments[1]->intValue();
        }

        if (!is_int($op)) {
            throw new OperandTypeException("Instruction INT2CHAR expects integer argument");
        }
        /** @var string|false $result */
        $result = mb_chr((int)($op), 'UTF-8');
        if ($result === false) {
            throw new StringOperationException("Instruction INT2CHAR failed to convert integer to character");
        }

        $arg = new Argument("string", $result, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
