<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// POPFRAME
class InstructionPOPFRAME extends Instruction {

    public function execute(): void {
        $this->exec->popFrame();
    }
}
