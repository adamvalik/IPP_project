<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// MOVE <var> <symb>
class InstructionMOVE extends Instruction {

    /**
     * Copies the <symb> to the <var>
     */
    public function execute(): void {
        // $var = $this->exec->getVariable($this->getArg(1)->getValue());
        // $symb = $this->getArg(2);

        // $this->exec->setVariable($var, $symb);
    }
}