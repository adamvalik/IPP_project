<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Instruction;

// JUMPIFEQ <label> <symb1> <symb2>
class InstructionJUMPIFEQ extends Instruction {

    public function execute(): void {
        $type1 = $type2 = $op1 = $op2 = "";
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
        if ($type1 !== $type2 && $type1 !== "nil" && $type2 !== "nil") {
            throw new OperandTypeException("Instruction JUMPIFEQ expects two arguments of the same type");
        }
        if ($type1 === "nil" && $type2 === "nil") {
            $result = true;
        } else if ($type1 === "nil" || $type2 === "nil") {
            $result = false;
        } else {
            $result = $op1 === $op2;
        }

        if ($result) {
            $this->runEnv->setIPtoLabel($this->arguments[0]->getLabel());
        }
    }
}
