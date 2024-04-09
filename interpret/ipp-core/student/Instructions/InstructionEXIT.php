<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\OperandValueException;
use IPP\Student\Instruction;

// EXIT <symb>
class InstructionEXIT extends Instruction {

    public function execute(): void {
        if ($this->arguments[0]->getType() === 'var') {
            $var = $this->exec->getVariable($this->arguments[0]->getVarName());
            if ($var->getType() !== 'int') {
                throw new OperandTypeException("Instruction EXIT expects an integer argument");
            }
            if (!is_int($var->getValue())) {
                throw new OperandTypeException("Instruction EXIT expects an integer argument");
            }
            $ret_code = $var->getValue();
        } else {
            if ($this->arguments[0]->getType() !== 'int') {
                throw new OperandTypeException("Instruction EXIT expects an integer argument");
            }
            $ret_code = $this->arguments[0]->intValue();
        }

        if ($ret_code < 0 || $ret_code > 9) {
            throw new OperandValueException("Instruction EXIT expects an integer argument in range 0-9");
        }

        $this->interpreter->setRetCode($ret_code);
        $this->runEnv->exit();
    }
}
