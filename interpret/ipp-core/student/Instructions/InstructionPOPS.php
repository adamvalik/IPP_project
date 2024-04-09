<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Instruction;

// POPS <var>
class InstructionPOPS extends Instruction {

    public function execute(): void {
        $this->exec->setVariable($this->arguments[0]->getVarName(), $this->runEnv->popData());
    }
}
