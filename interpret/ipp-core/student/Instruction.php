<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class Instruction {

    protected int $order;
    protected Interpreter $interpreter;

    /**
     * @var array<Argument>
     */
    protected $arguments = [];

    public function __construct(DOMElement $instructionElement, Interpreter $interpreter) {
        $this->interpreter = $interpreter;
        $this->order = intval($instructionElement->getAttribute('order'));
        $this->parseArguments($instructionElement);
    }

    public function getOrder(): int {
        return $this->order;
    }

    public function getOpcode(): string {
        return static::class;
    }

    public function parseArguments(DOMElement $instructionElement): void {
         // parse arguments
         for ($i = 1; $i <= 3; $i++) {
            $arg = $instructionElement->getElementsByTagName('arg' . $i);
            if ($arg->length === 1 && $arg[0] instanceof DOMElement) {
                $this->arguments[] = new Argument($arg[0]->getAttribute('type'), $arg[0]->nodeValue, $i);
            }
            else if ($arg->length > 1) {
                throw new XMLStructureException("Invalid arguments, more occurences of one argument");
            }
        }

        // check there are no other elements
        $elementCount = 0;
        foreach ($instructionElement->childNodes as $childNode) {
            if ($childNode instanceof DOMElement) {
                $elementCount++;
            }
        }
        if ($elementCount !== count($this->arguments)) {
            throw new XMLStructureException("Invalid element in instruction element (only arg1, arg2, arg3 are allowed)");
        }

        // check the order of arguments
        for ($i = 1; $i <= count($this->arguments); $i++) {
            if ($this->arguments[$i - 1]->getArgOrder() === $i) {
                // validate its type and value
                $this->arguments[$i - 1]->validate();
            }
            else {
                throw new XMLStructureException("Invalid argument order, arg" . $i . " is missing");
            }
        }
    }

    abstract public function execute(): void;
}
