<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// PUSHS <symb>
class InstructionPUSHS extends Instruction {

    public function execute(): void {
        $this->runEnv->pushData($this->arguments[0]);
    }
}
