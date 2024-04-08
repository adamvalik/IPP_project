<?php

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// BREAK
class InstructionBREAK extends Instruction {

    public function execute(): void {
        $this->interpreter->errorWriter()->writeString("hej cavo");
    }
}
