<?php
/**
 * IPP - PHP Project Student
 * 
 * @author Adam Valík <xvalik05>
 */


namespace IPP\Student;

use DOMElement;
use IPP\Core\AbstractInterpreter;
use IPP\Core\ReturnCode;


class Interpreter extends AbstractInterpreter {

    public function execute(): int {
        $dom = $this->source->getDOMDocument();  
        XMLValidator::validateXML($dom);
        $programElement = $dom->documentElement;     
        
        $instructions = [];

        if ($programElement !== null && $programElement->hasChildNodes()) {
            // create a list of instructions
            foreach ($programElement->childNodes as $DOMInstruction) {
                if ($DOMInstruction instanceof DOMElement) {
                    $instructions[] = InstructionFactory::createInstruction($DOMInstruction, $this);
                }
            }
        }
        else {
            // there is only <program> element
            return ReturnCode::OK;
        }

        // sort the instructions by order
        usort($instructions, function($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });

        // execute the instructions
        $instruction_pointer = 0;

        while ($instruction_pointer < count($instructions)) {
            $instructions[$instruction_pointer]->execute();
            $instruction_pointer++;
        }

        return ReturnCode::OK;
    }
}