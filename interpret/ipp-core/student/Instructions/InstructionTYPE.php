<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Instruction;

// TYPE <var> <symb>
class InstructionTYPE extends Instruction {

    public function execute(): void {
        if ($this->arguments[1]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[1]->getVarName());
            $var->isInitialized() ? $type = $var->getType() : $type = '';
        } else {
            $type = $this->arguments[1]->getType();
        }
        $this->exec->setVariable($this->arguments[0]->getVarName(), new Argument("string", $type, 0));
    }
}
