<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// POPS <var>
class InstructionPOPS extends Instruction {

    public function execute(): void {
        $this->exec->setVariable($this->arguments[0]->getVarName(), $this->runEnv->popData());
    }
}
