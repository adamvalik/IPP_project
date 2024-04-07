<?php

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;

// EQ <var> <symb1> <symb2>
class InstructionEQ extends Instruction {

    public function execute(): void {
        $type1 = $type2 = "";
        $op1 = $op2 = null;
        for ($i = 1; $i <= 2; $i++) {
            if ($this->arguments[$i]->getType() === 'var') {
                $type = $this->exec->getVariable($this->arguments[$i]->getVarName())->getType();
                $op = $this->exec->getVariable($this->arguments[$i]->getVarName())->getValue();
                $i === 1 ? $type1 = $type : $type2 = $type;
                $i === 1 ? $op1 = $op : $op2 = $op;
            } else {
                $type = $this->arguments[$i]->getType();
                $op = $this->arguments[$i]->getSymbValue();
                $i === 1 ? $type1 = $type : $type2 = $type;
                $i === 1 ? $op1 = $op : $op2 = $op;
            }
        }
        if ($type1 === "nil" && $type2 === "nil") {
            throw new OperandTypeException("Instruction EQ cannot compare two nil values");
        }
        if ($type1 !== $type2 ) {
            throw new OperandTypeException("Instruction GT expects two arguments of the same type");
        }
        
        $result = $op1 == $op2 ? "true" : "false";

        $arg = new Argument("bool", $result, 0);
        $this->exec->setVariable($this->arguments[0]->getVarName(), $arg);
    }
}
