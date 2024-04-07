<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// CREATEFRAME
class InstructionCREATEFRAME extends Instruction {

    public function execute(): void {
        $this->exec->createTmpFrame();
    }
}
