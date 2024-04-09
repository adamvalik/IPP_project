<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\StringOperationException;
use IPP\Student\Instruction;

// GETCHAR <var> <symb1> <symb2>
class InstructionGETCHAR extends Instruction {

    public function execute(): void {
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'string') {
                throw new OperandTypeException("Instruction GETCHAR expects string as second argument");
            }
            $op1 = $var->getValue();
        } else {
            if ($this->arguments[1]->getType() !== 'string') {
                throw new OperandTypeException("Instruction GETCHAR expects string as second argument");
            }
            $op1 = $this->arguments[1]->stringValue();
        }

        if ($this->arguments[2]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[2]->getVarName());
            if ($var->getType() !== 'int') {
                throw new OperandTypeException("Instruction GETCHAR expects integer as third argument");
            }
            $op2 = $var->getValue();
        } else {
            if ($this->arguments[2]->getType() !== 'int') {
                throw new OperandTypeException("Instruction GETCHAR expects integer as third argument");
            }
            $op2 = $this->arguments[2]->intValue();
        }

        if (!is_string($op1)) {
            throw new OperandTypeException("Instruction GETCHAR expects string as second argument");
        }
        if (!is_int($op2)) {
            throw new OperandTypeException("Instruction GETCHAR expects integer as third argument");
        }
        if ($op2 < 0 || $op2 >= strlen($op1)) {
            throw new StringOperationException("Instruction GETCHAR index out of bounds");
        }
        $char = $op1[$op2];
        $arg = new Argument("string", $char, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}