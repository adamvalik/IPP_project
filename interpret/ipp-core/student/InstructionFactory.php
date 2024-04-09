<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class InstructionFactory {

    public static function createInstruction(DOMElement $nodeInstruction, Interpreter $interpret, ExecutionContext $exec, RuntimeEnv $run): Instruction {
        
        // create class name from opcode
        $opcode = $nodeInstruction->getAttribute('opcode');
        $namespace = 'IPP\Student\Instructions\\';
        $className = $namespace . "Instruction" . strtoupper($opcode);

        // exceptions here should be already handled in the XMLValidator
        if (class_exists($className) && is_subclass_of($className, Instruction::class)) {
            // based on the opcode, return the object of the corresponding instruction class
            return new $className($nodeInstruction, $interpret, $exec, $run);
        }
        throw new XMLStructureException("Invalid opcode '$opcode' in XML structure.");
    }
}
