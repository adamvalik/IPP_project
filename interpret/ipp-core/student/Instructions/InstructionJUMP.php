<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// JUMP <label>
class InstructionJUMP extends Instruction {

    public function execute(): void {
        $this->runEnv->setIPtoLabel($this->arguments[0]->getLabel());
    }
}
