<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class Instruction {

    protected int $order;
    protected Interpreter $interpreter;
    protected ExecutionContext $executionContext;
    /**
     * @var array<string>
     */
    protected $data_stack;
    /**
     * @var array<int>
     */
    protected $call_stack;

    /**
     * @var array<Argument>
     */
    protected $arguments = [];

    public function __construct(DOMElement $instructionElement, Interpreter $interpreter, ExecutionContext $executionContext, array &$data_stack, array &$call_stack) {
        $this->interpreter = $interpreter;
        $this->executionContext = $executionContext;
        $this->data_stack = &$data_stack;
        $this->call_stack = &$call_stack;
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
                if ($arg[0]->nodeValue === null) {
                    throw new XMLStructureException("Invalid argument value");
                }
                $this->arguments[] = new Argument($arg[0]->getAttribute('type'), $arg[0]->nodeValue, $i);
            }
            else if ($arg->length > 1) {
                throw new XMLStructureException("Invalid arguments, more occurences of one argument");
            }
        }
    }

    public function getArg(int $order): Argument {
        foreach ($this->arguments as $argument) {
            if ($argument->getArgOrder() === $order) {
                return $argument;
            }
        }
        throw new XMLStructureException("Argument with order $order not found");
    }

    abstract public function execute(): void;
}
