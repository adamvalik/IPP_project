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
use IPP\Student\Exceptions\XMLFormatException;
use IPP\Student\Exceptions\XMLStructureException;


//https://www.w3.org/TR/xml/#sec-well-formed

class Interpreter extends AbstractInterpreter {

    public function execute(): int {
        $dom = $this->source->getDOMDocument();   
        $programElement = $dom->documentElement;     
        
        // validate the root element <program>
        if ($programElement !== null && 
        ($programElement->tagName !== null && $programElement->tagName !== 'program' 
        || $programElement->getAttribute('language') !== null && $programElement->getAttribute('language') !== 'IPPcode24')) {
            throw new XMLFormatException();
        }
        
        $instructions = [];

        if ($programElement !== null && $programElement->hasChildNodes()) {
            // create a list of instructions
            foreach ($programElement->childNodes as $DOMInstruction) {
                if ($DOMInstruction instanceof DOMElement) {
                    if ($DOMInstruction->tagName !== 'instruction') {
                        // <program> can contain only <instruction> elements
                        throw new XMLStructureException();
                    }
            
                    $instructions[] = InstructionFactory::createInstruction($DOMInstruction, $this); 
                }
            }
        }
        else {
            // there is only <program> element
            $this->stdout->writeString("Only program element\n");
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