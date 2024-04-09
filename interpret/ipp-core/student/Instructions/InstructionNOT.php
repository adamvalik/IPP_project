<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;
use IPP\Student\Argument;

// NOT <var> <symb>
class InstructionNOT extends Instruction {

    public function execute(): void {
        $op = false;
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            if ($var->getType() !== 'bool') {
                throw new OperandTypeException("Instruction NOT expects boolean argument");
            }
            if (is_bool($var->getValue()) === false) {
                throw new OperandTypeException("Instruction NOT expects boolean argument");
            }
            $op = $var->getValue();
        } else {
            if ($this->arguments[1]->getType() !== 'bool') {
                throw new OperandTypeException("Instruction NOT expects boolean argument");
            }
            $op = $this->arguments[1]->boolValue();
        }

        $result = !$op ? "true" : "false";

        $arg = new Argument("bool", $result, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
