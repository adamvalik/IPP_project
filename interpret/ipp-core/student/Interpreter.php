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
        $exec = new ExecutionContext();
        $run = new RuntimeEnv();

        $dom = $this->source->getDOMDocument();  

        
        // validate the XML
        XMLValidator::validateXML($dom);
        
        $programElement = $dom->documentElement;

        
        $instructions = [];

        if ($programElement !== null && $programElement->hasChildNodes()) {
            // create a list of instructions
            foreach ($programElement->childNodes as $DOMInstruction) {
                if ($DOMInstruction instanceof DOMElement) {
                    $instructions[] = InstructionFactory::createInstruction($DOMInstruction, $this, $exec, $run);
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

        // create a label map
        $run->createLabelMap($instructions);

        // execute the instructions
        while ($run->IP() < count($instructions)) {
            $instructions[$run->IP()]->execute();
            $run->incIP();
        }

        return ReturnCode::OK;
    }
}
