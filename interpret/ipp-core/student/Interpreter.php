<?php
/**
 * IPP - PHP Project Student
 * 
 * @author Adam Valík <xvalik05>
 */


namespace IPP\Student;

use DOMElement;
use IPP\Core\AbstractInterpreter;
use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Core\Interface\SourceReader;



class Interpreter extends AbstractInterpreter {
    private int $ret_code = 0;

    public function execute(): int {
        $exec = new ExecutionContext();
        $run = new RuntimeEnv();

        $dom = $this->source->getDOMDocument();  

        
        // validate the XML
        XMLValidator::validateXML($dom);
        
        $programElement = $dom->documentElement;

        /**
         * @var array<Instruction>
         */
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
            return $this->ret_code;
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

        return $this->ret_code;
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

    public function setRetCode(int $ret_code): void {
        $this->ret_code = $ret_code;
    }
}
