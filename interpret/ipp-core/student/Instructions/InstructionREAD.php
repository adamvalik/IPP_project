<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Instructions;

use IPP\Student\Argument;
use IPP\Student\Instruction;

// READ <var> <type>
class InstructionREAD extends Instruction {

    public function execute(): void {
        $value = '';
        $type = $this->arguments[1]->getTypeValue();
        switch ($type) {
            case 'int':
                $value = $this->interpreter->reader()->readInt();
                is_int($value) ? $value = (string)$value : $value = null;
                break;
            case 'string':
                $value = $this->interpreter->reader()->readString();
                if (!is_string($value)) {
                    $value = null;
                }
                break;
            case 'bool':
                $value = $this->interpreter->reader()->readBool();
                // double ternary operator, love it
                is_bool($value) ? $value = $value ? 'true' : 'false' : $value = null;
                break;
        }
        if ($value === null) {
            $this->exec->setVariable($this->arguments[0]->getVarName(), new Argument('nil', 'nil', 0));
        }
        else {
            $this->exec->setVariable($this->arguments[0]->getVarName(), new Argument($type, $value, 0));
        }
    }
}
