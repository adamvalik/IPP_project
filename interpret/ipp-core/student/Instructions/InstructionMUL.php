<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Instruction;
use IPP\Student\Exceptions\OperandTypeException;

// MUL <var> <symb1> <symb2>
class InstructionMUL extends Instruction {

    public function execute(): void {
        // symb can be either variable of type int or constant of type int
        $op1 = $op2 = 0;
        for ($i = 1; $i <= 2; $i++) {
            if ($this->arguments[$i]->getType() === 'var') {
                // int variable
                $var = $this->exec->getVariable($this->arguments[$i]->getVarName());
                if ($var->getType() !== 'int') {
                    throw new OperandTypeException("Instruction ADD expects two integer arguments");
                }
                if (is_int($var->getValue()) === false) {
                    throw new OperandTypeException("Instruction ADD expects two integer arguments");
                }
                $i === 1 ? $op1 = $var->getValue() : $op2 = $var->getValue();
            } else { 
                // int literal
                if ($this->arguments[$i]->getType() !== 'int') {
                    throw new OperandTypeException("Instruction ADD expects two integer arguments");
                }
                $i === 1 ? $op1 = $this->arguments[$i]->intValue() : $op2 = $this->arguments[$i]->intValue();
            }
        }
        // converting int to string and back to int should not pose any problems, it is done to keep the consistency of the framework design
        $arg = new Argument("int", (string)($op1 * $op2), 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
