<?php

namespace IPP\Student;

use DOMElement;
use IPP\Student\Exceptions\XMLStructureException;

abstract class Instruction {

    protected int $order;
    protected Interpreter $interpreter;
    protected ExecutionContext $exec;
    protected RuntimeEnv $runEnv;

    /**
     * @var array<Argument>
     */
    protected $arguments = [];

    public function __construct(DOMElement $instructionElement, Interpreter $interpreter, ExecutionContext $executionContext, RuntimeEnv $runtimeEnv) {
        $this->interpreter = $interpreter;
        $this->exec = $executionContext;
        $this->runEnv = $runtimeEnv;
        $this->order = intval($instructionElement->getAttribute('order'));
        $this->parseArguments($instructionElement);
    }

    public function getOrder(): int {
        return $this->order;
    }

    public function getClassName(): string {
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
