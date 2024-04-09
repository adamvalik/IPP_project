<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;

// STRLEN <var> <symb>
class InstructionSTRLEN extends Instruction {

    public function execute(): void {
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'string') {
                throw new OperandTypeException("Instruction STRLEN expects a string argument");
            }
            if (!is_string($var->getValue())) {
                throw new OperandTypeException("Instruction STRLEN expects a string argument");
            }
            $arg = new Argument("int", (string)strlen($var->getValue()), 0);
        } else {
            if ($this->arguments[1]->getType() !== 'string') {
                throw new OperandTypeException("Instruction STRLEN expects a string argument");
            }
            $arg = new Argument("int", (string)strlen($this->arguments[1]->stringValue()), 0);
        }
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}