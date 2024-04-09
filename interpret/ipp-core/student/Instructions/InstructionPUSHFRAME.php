<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// PUSHFRAME
class InstructionPUSHFRAME extends Instruction {

    public function execute(): void {
        $this->exec->pushFrame();
    }
}
