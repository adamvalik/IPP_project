<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 * 
 * Entry point of the IPP\Student (called by IPP\Core\Engine)
 */

namespace IPP\Student;

use DOMElement;
use IPP\Core\AbstractInterpreter;
use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;

class Interpreter extends AbstractInterpreter {

    // return code, default is 0, can be changed by the EXIT instruction
    private int $retCode = 0;

    public function execute(): int {
        $dom = $this->source->getDOMDocument();  
        
        // validate the XML structure
        XMLValidator::validateXML($dom);
        
        $programElement = $dom->documentElement;
        
        /** @var array<Instruction> */
        $instructions = [];
        $exec = new ExecutionContext();
        $run = new RuntimeEnv();

        if ($programElement !== null && $programElement->hasChildNodes()) {
            // create a list of instructions
            foreach ($programElement->childNodes as $DOMInstruction) {
                if ($DOMInstruction instanceof DOMElement) {
                    // instruction factory handles the instantiation of the correct instruction based on the opcode
                    $instructions[] = InstructionFactory::createInstruction($DOMInstruction, $this, $exec, $run);
                }
            }
        }
        else {
            // there is only <program> element
            return $this->retCode;
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

        return $this->retCode;
    }

    public function reader(): InputReader {
        return $this->input;
    }

    public function writer(): OutputWriter {
        return $this->stdout;
    }

    public function errorWriter(): OutputWriter {
        return $this->stderr;
    }

    public function setRetCode(int $retCode): void {
        $this->retCode = $retCode;
    }
}
