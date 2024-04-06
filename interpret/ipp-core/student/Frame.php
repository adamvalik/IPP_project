<?php

namespace IPP\Student;

use IPP\Student\Exceptions\NonExistingVariableException;
use IPP\Student\Exceptions\SemanticException;

class Frame {
    /**
     * @var array<Variable>
     */
    private array $variables;

    public function __construct() {
        $this->variables = [];
    }

    // add the variable with the given name, throw an exception if the variable already exists
    public function addVariable(string $name): void {
        !isset($this->variables[$name]) ? $this->variables[$name] = new Variable($name) : throw new SemanticException("Variable '$name' already exists");
    }

    // set the variable with the given name, throw an exception if the variable does not exist
    public function setVariable(string $name): void {
        isset($this->variables[$name]) ? $this->variables[$name]->setValue($name) : throw new NonExistingVariableException("Variable $name not found");
    }

    // return the variable with the given name, throw an exception if the variable does not exist
    public function getVariable(string $name): Variable {
        return isset($this->variables[$name]) ? $this->variables[$name] : throw new NonExistingVariableException("Variable $name not found");
    }   
}