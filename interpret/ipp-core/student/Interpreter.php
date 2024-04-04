<?php
/**
 * IPP - PHP Project Student
 * 
 * @author Adam Valík <xvalik05>
 */


namespace IPP\Student;

use DOMElement;
use DOMNode;
use DOMNodeList;
use IPP\Core\AbstractInterpreter;
use IPP\Core\ReturnCode;


class Interpreter extends AbstractInterpreter {
    public function execute(): int {
        $dom = $this->source->getDOMDocument();
        $instructionPointer = 0;
   



        /***** XML VALIDATION *****/

        // Ensure the root element is 'program' and has the required 'language' attribute 
        // if ($dom->documentElement !== null && ($dom->documentElement->tagName !== null && $dom->documentElement->tagName !== 'program' || $dom->documentElement->getAttribute('language') !== null && $dom->documentElement->getAttribute('language') !== 'IPPcode24')) {
        //     return 32; // Unexpected XML structure
        // }

        // $ordersSeen = []; // Track seen 'order' attributes

        // foreach ($dom->getElementsByTagName('instruction') as $instruction) {
        //     $order = $instruction->getAttribute('order');
            
        //     // Check for duplicate or negative 'order' values
        //     if (in_array($order, $ordersSeen) || $order < 0) {
        //         return 32;
        //     }
        //     $ordersSeen[] = $order;
        // }

        /****************************/


        $DOMInstructions = $dom->getElementsByTagName('instruction');
        $instructions = [];

        // create a list of instructions
        foreach ($DOMInstructions as $DOMInstruction) {
            $instructions[] = InstructionFactory::createInstruction($DOMInstruction); 
            // can return null but expects that the XML was validated thus only valid opcodes are present
        }


        return ReturnCode::OK;
    }
}



abstract class InstructionFactory {
    public static function createInstruction(DOMElement $nodeInstruction): ?Instruction {
        $opcode = $nodeInstruction->getAttribute('opcode');
        $className = "Instruction" . strtoupper($opcode);
        if (class_exists($className) && is_subclass_of($className, Instruction::class)) {
            return new $className($nodeInstruction);
        }
        return null; // Or handle unknown opcode with an appropriate strategy
    }
}

class Argument {
    private string $type;
    private ?string $value;

    public function __construct(string $type, ?string $value) {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getValue(): ?string {
        return $this->value;
    }
}

abstract class Instruction {

    protected int $order;

    /**
     * @var array<Argument>
     */
    protected $arguments = [];

    public function __construct(DOMElement $instructionElement) {
        $this->order = intval($instructionElement->getAttribute('order'));

        // parse arguments
        foreach ($instructionElement->childNodes as $arg) {
            if ($arg instanceof DOMElement && 
                    ($arg->tagName == 'arg1' && count($this->arguments) == 0  
                    || $arg->tagName == 'arg2' && count($this->arguments) == 1
                    || $arg->tagName == 'arg3' && count($this->arguments) == 2))
            {
                $this->arguments[] = new Argument($arg->getAttribute('type'), $arg->nodeValue);
            }
        }
    }

    abstract public function execute(): void;
}




// MOVE <var> <symb>
class InstructionMOVE extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// CREATEFRAME
class InstructionCREATEFRAME extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// PUSHFRAME
class InstructionPUSHFRAME extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// POPFRAME
class InstructionPOPFRAME extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// DEFVAR <var>
class InstructionDEFVAR extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// CALL <label>
class InstructionCALL extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// RETURN
class InstructionRETURN extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// PUSHS <symb>
class InstructionPUSHS extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// POPS <var>
class InstructionPOPS extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// ADD <var> <symb1> <symb2>
class InstructionADD extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// SUB <var> <symb1> <symb2>
class InstructionSUB extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// MUL <var> <symb1> <symb2>
class InstructionMUL extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// IDIV <var> <symb1> <symb2>
class InstructionIDIV extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// LT <var> <symb1> <symb2>
class InstructionLT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// GT <var> <symb1> <symb2>
class InstructionGT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// EQ <var> <symb1> <symb2>
class InstructionEQ extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// AND <var> <symb1> <symb2>
class InstructionAND extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// OR <var> <symb1> <symb2>
class InstructionOR extends Instruction {

    public function execute(): void {
        // Implementation
    }
}   

// NOT <var> <symb>
class InstructionNOT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// INT2CHAR <var> <symb>
class InstructionINT2CHAR extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// STRI2INT <var> <symb1> <symb2>
class InstructionSTR2INT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// READ <var> <type>
class InstructionREAD extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// WRITE <symb>
class InstructionWRITE extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// CONCAT <var> <symb1> <symb2>
class InstructionCONCAT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// STRLEN <var> <symb>
class InstructionSTRLEN extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// GETCHAR <var> <symb1> <symb2>
class InstructionGETCHAR extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// SETCHAR <var> <symb1> <symb2>
class InstructionSETCHAR extends Instruction {

    public function execute(): void {
        // Implementation
    }
}   

// TYPE <var> <symb>
class InstructionTYPE extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// LABEL <label>
class InstructionLABEL extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// JUMP <label>
class InstructionJUMP extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// JUMPIFEQ <label> <symb1> <symb2>
class InstructionJUMPIFEQ extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// JUMPIFNEQ <label> <symb1> <symb2>
class InstructionJUMPIFNEQ extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// EXIT <symb>
class InstructionEXIT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// DPRINT <symb>
class InstructionDPRINT extends Instruction {

    public function execute(): void {
        // Implementation
    }
}

// BREAK
class InstructionBREAK extends Instruction {

    public function execute(): void {
        // Implementation
    }
}