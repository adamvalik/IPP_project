<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// RETURN
class InstructionRETURN extends Instruction {

    public function execute(): void {
        $this->runEnv->setIP($this->runEnv->popCall());
    }
}
