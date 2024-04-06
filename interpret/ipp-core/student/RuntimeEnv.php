<?php

namespace IPP\Student;

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
        return array_pop($this->dataStack);
    }

    public function pushCall(int $call): void {
        $this->callStack[] = $call;
    }

    public function popCall(): int {
        return array_pop($this->callStack);
    }
    
}