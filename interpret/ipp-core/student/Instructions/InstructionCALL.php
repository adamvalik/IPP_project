<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// CALL <label>
class InstructionCALL extends Instruction {

    public function execute(): void {
        $label = $this->arguments[0]->getLabel();
        $this->runEnv->pushCall($this->runEnv->nextIP());
        $this->runEnv->setIPtoLabel($label);
    }
}
