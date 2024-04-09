<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// MOVE <var> <symb>
class InstructionMOVE extends Instruction {

    public function execute(): void {
        $var = $this->arguments[0]->getVarName();
        $symb = $this->arguments[1];

        $this->exec->setVariable($var, $symb);
    }
}