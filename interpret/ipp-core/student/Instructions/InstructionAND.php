<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;
use IPP\Student\Argument;

// AND <var> <symb1> <symb2>
class InstructionAND extends Instruction {

    public function execute(): void {
        $op1 = $op2 = false;
        for ($i = 1; $i <= 2; $i++) {
            if ($this->arguments[$i]->getType() === 'var') {
                $var = $this->exec->getVariable($this->arguments[$i]->getVarName());
                if ($var->getType() !== 'bool') {
                    throw new OperandTypeException("Instruction AND expects two boolean arguments");
                }
                if (is_bool($var->getValue()) === false) {
                    throw new OperandTypeException("Instruction AND expects two boolean arguments");
                }
                $i === 1 ? $op1 = $var->getValue() : $op2 = $var->getValue();
            } else {
                if ($this->arguments[$i]->getType() !== 'bool') {
                    throw new OperandTypeException("Instruction AND expects two boolean arguments");
                }
                $i === 1 ? $op1 = $this->arguments[$i]->boolValue() : $op2 = $this->arguments[$i]->boolValue();
            }
        }

        $result = $op1 && $op2 ? "true" : "false";

        $arg = new Argument("bool", $result, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
