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

    //private array $label_map = [];

    public function execute(): int {
        $executionContext = new ExecutionContext();
        $dom = $this->source->getDOMDocument();  
        
        // validate the XML
        XMLValidator::validateXML($dom);
        
        $programElement = $dom->documentElement;     
        
        $instructions = [];

        if ($programElement !== null && $programElement->hasChildNodes()) {
            // create a list of instructions
            foreach ($programElement->childNodes as $DOMInstruction) {
                if ($DOMInstruction instanceof DOMElement) {
                    $instructions[] = InstructionFactory::createInstruction($DOMInstruction, $this, $executionContext);
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

        // create label map
        // $this->label_map = [];
        // for ($i = 0; $i < count($instructions); $i++) {
        //     if ($instructions[$i] instanceof Instructions\InstructionLABEL) {
        //         // map the label to the index of the instruction
        //         $this->label_map[$instructions[$i]->getArg(1)->getValue()] = $i; // or $instructions[$i]->getOrder();
        //     }
        // }

        // execute the instructions
        $instruction_pointer = 0;

        // while ($instruction_pointer < count($instructions)) {
        //     $instructions[$instruction_pointer]->execute();
        //     $instruction_pointer++;
        // }

        return ReturnCode::OK;
    }
}