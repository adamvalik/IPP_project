<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;

// CONCAT <var> <symb1> <symb2>
class InstructionCONCAT extends Instruction {

    public function execute(): void {
        $op1 = $op2 = '';
        for ($i = 1; $i <= 2; $i++) {
            if ($this->arguments[$i]->getType() === 'var') {
                $var = $this->exec->getVariable($this->arguments[$i]->getVarName());
                if ($var->getType() !== 'string') {
                    throw new OperandTypeException("Instruction CONCAT expects two string arguments");
                }
                $i === 1 ? $op1 = $var->getValue() : $op2 = $var->getValue();
            } else { 
                if ($this->arguments[$i]->getType() !== 'string') {
                    throw new OperandTypeException("Instruction CONCAT expects two string arguments");
                }
                $i === 1 ? $op1 = $this->arguments[$i]->stringValue() : $op2 = $this->arguments[$i]->stringValue();
            }
        }
        if (!is_string($op1) || !is_string($op2)) {
            throw new OperandTypeException("Instruction CONCAT expects two string arguments");
        }
        $arg = new Argument("string", $op1 . $op2, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}