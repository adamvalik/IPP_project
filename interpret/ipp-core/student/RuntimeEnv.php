<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\SemanticException;

class RuntimeEnv {
    /**
     * @var array<string,int> 
     */
    private array $labelMap = [];

    private int $instrPtr = 0;
    /**
     * @var array<Argument>
     */
    private array $dataStack = [];
    /**
     * @var array<int>
     */
    private array $callStack = [];

    private int $instr_len = 0;

    /**
     * @param array<Instruction> $instructions
     */
    public function createLabelMap(array $instructions): void {
        $this->instr_len = count($instructions);
        for ($i = 0; $i < count($instructions); $i++) {
            if ($instructions[$i] instanceof Instructions\InstructionLABEL) {
                // map the label to the index of the instruction, error if the label already exists
                if (array_key_exists($instructions[$i]->getArg(1)->getLabel(), $this->labelMap)) {
                    throw new SemanticException("Label ".$instructions[$i]->getArg(1)->getLabel()." already exists");
                }
                $this->labelMap[$instructions[$i]->getArg(1)->getLabel()] = $i;
            }
        }
    }

    public function setIPtoLabel(string $label): void {
        if (!array_key_exists($label, $this->labelMap)) {
            throw new SemanticException("Label $label not found");
        }
        $this->instrPtr = $this->labelMap[$label];
    }

    public function setIP(int $ip): void {
        $this->instrPtr = $ip;
    }

    public function IP(): int {
        return $this->instrPtr;
    }

    public function incIP(): void {
        $this->instrPtr++;
    }

    public function nextIP(): int {
        return $this->instrPtr++;
    }

    public function exit(): void {
        // set IP to the end of the instructions
        $this->instrPtr = $this->instr_len; 
    }

    public function pushData(Argument $data): void {
        $this->dataStack[] = $data;
    }

    public function popData(): Argument {
        return !empty($this->dataStack) ? array_pop($this->dataStack) : throw new MissingValueException("Data stack is empty");
    }

    public function pushCall(int $call): void {
        $this->callStack[] = $call;
    }

    public function popCall(): int {
        return !empty($this->callStack) ? array_pop($this->callStack) : throw new MissingValueException("Call stack is empty");
    }
}