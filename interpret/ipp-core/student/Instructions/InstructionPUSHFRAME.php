<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// PUSHFRAME
class InstructionPUSHFRAME extends Instruction {

    public function execute(): void {
        $this->exec->pushFrame();
    }
}
