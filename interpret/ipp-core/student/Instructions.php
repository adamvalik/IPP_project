<?php

namespace IPP\Student\Instructions;


interface Instruction {

    public function execute(): void;

}

abstract class InstructionFactory {
    public static function createInstruction(string $opcode): ?Instruction {
        $className = "Instruction" . strtoupper($opcode);
        if (class_exists($className) && is_subclass_of($className, Instruction::class)) {
            return new $className();
        }
        return null; // Or handle unknown opcode with an appropriate strategy
    }
}



// class InstructionMOVE extends Instruction {

// }

// class InstructionCREATEFRAME extends Instruction {

// }

// class InstructionPUSHFRAME extends Instruction {

// }

// class InstructionDEFVAR extends Instruction {

// }

// class InstructionCALL extends Instruction {

// }

// class InstructionRETURN extends Instruction {

// }

// class InstructionPUSHS extends Instruction {

// }

// class InstructionPOPS extends Instruction {

// }

// class InstructionADD extends Instruction {

// }

// class InstructionSUB extends Instruction {

// }

// class InstructionMUL extends Instruction {

// }

// class InstructionIDIV extends Instruction {

// }

// class InstructionLT extends Instruction {

// }

// class InstructionGT extends Instruction {

// }

// class InstructionEQ extends Instruction {

// }

// class InstructionAND extends Instruction {

// }

// class InstructionOR extends Instruction {

// }   

// class InstructionNOT extends Instruction {

// }

// class InstructionINT2CHAR extends Instruction {

// }

// class InstructionSTR2INT extends Instruction {

// }

// class InstructionREAD extends Instruction {

// }

// class InstructionWRITE extends Instruction {

// }

// class InstructionCONCAT extends Instruction {

// }

// class InstructionSTRLEN extends Instruction {

// }

// class InstructionGETCHAR extends Instruction {

// }

// class InstructionSETCHAR extends Instruction {

// }   

// class InstructionTYPE extends Instruction {

// }

// class InstructionLABEL extends Instruction {

// }

// class InstructionJUMP extends Instruction {

// }

// class InstructionJUMPIFEQ extends Instruction {

// }

// class InstructionJUMPIFNEQ extends Instruction {

// }

// class InstructionEXIT extends Instruction {

// }

// class InstructionDPRINT extends Instruction {

// }

// class InstructionBREAK extends Instruction {

// }