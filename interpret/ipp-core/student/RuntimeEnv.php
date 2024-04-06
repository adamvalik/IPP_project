<?php

namespace IPP\Student;

use IPP\Student\Exceptions\MissingValueException;

class RuntimeEnv {
    /**
     * @var array<string,int> 
     */
    private array $labelMap = [];

    private int $instrPtr = 0;
    /**
     * @var array<string>
     */
    private array $dataStack = [];
    /**
     * @var array<int>
     */
    private array $callStack = [];

    /**
     * @param array<Instruction> $instructions
     */
    public function createLabelMap(array $instructions): void {
        for ($i = 0; $i < count($instructions); $i++) {
            if ($instructions[$i] instanceof Instructions\InstructionLABEL) {
                // map the label to the index of the instruction
                $this->labelMap[$instructions[$i]->getArg(1)->getValue()] = $i;
            }
        }
    }

    public function setIPtoLabel(string $label): void {
        $this->instrPtr = $this->labelMap[$label];
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

    public function pushData(string $data): void {
        $this->dataStack[] = $data;
    }

    public function popData(): string {
        return !empty($this->dataStack) ? array_pop($this->dataStack) : throw new MissingValueException("Data stack is empty");
    }

    public function pushCall(int $call): void {
        $this->callStack[] = $call;
    }

    public function popCall(): int {
        return !empty($this->callStack) ? array_pop($this->callStack) : throw new MissingValueException("Call stack is empty");
    }
    
}