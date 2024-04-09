<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// BREAK
class InstructionBREAK extends Instruction {

    public function execute(): void {
        $this->interpreter->errorWriter()->writeString("BREAK: instruction pointer: " . $this->runEnv->IP());
    }
}
