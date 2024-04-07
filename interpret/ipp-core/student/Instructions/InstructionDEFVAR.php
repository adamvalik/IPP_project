<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// DEFVAR <var>
class InstructionDEFVAR extends Instruction {

    public function execute(): void {
        $this->exec->addVariable($this->arguments[0]->getVarName());
    }
}
