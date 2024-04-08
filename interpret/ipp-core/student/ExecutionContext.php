<?php

namespace IPP\Student;

use IPP\Student\Exceptions\NonExistingFrameException;

class ExecutionContext {

    private Frame $globalFrame;
    private ?Frame $tmpFrame = null;
    /**
     * @var array<Frame>
     */
    private array $localFrames;

    public function __construct() {
        $this->globalFrame = new Frame();
        $this->tmpFrame = null;
        $this->localFrames = [];
    }

    public function addVariable(string $var): void {
        $scope = explode('@', $var)[0];
        $name = explode('@', $var)[1];

        if ($scope === 'GF') {
            $this->globalFrame->addVariable($name);
        } else if ($scope === 'TF') {
            $this->tmpFrame !== null ? $this->tmpFrame->addVariable($name) : throw new NonExistingFrameException("No temporary frame available");
        } else if ($scope === 'LF') {
            $this->currLocalFrame()->addVariable($name);
        }
    }

    public function setVariable(string $var, Argument $symb): void {
        $scope = explode('@', $var)[0];
        $name = explode('@', $var)[1];

        // symb can be literal or variable
        if ($symb->getType() === 'var') {
            // variable -> if arg is var, than its value is variable name
            if ($scope === 'GF') {
                $this->globalFrame->setVariable($name, $this->getVariable($symb->getVarName())->getType(), $this->getVariable($symb->getVarName())->getValue());
            } else if ($scope === 'TF') {
                $this->tmpFrame !== null ? $this->tmpFrame->setVariable($name, $this->getVariable($symb->getVarName())->getType(), $this->getVariable($symb->getVarName())->getValue()) : throw new NonExistingFrameException("No temporary frame available");
            } else if ($scope === 'LF') {
                $this->currLocalFrame()->setVariable($name, $this->getVariable($symb->getVarName())->getType(), $this->getVariable($symb->getVarName())->getValue());
            }
        }
        else {
            // literal -> int|bool|string|nil
            if ($scope === 'GF') {
                $this->globalFrame->setVariable($name, $symb->getType(), $symb->getSymbValue());
            } else if ($scope === 'TF') {
                $this->tmpFrame !== null ? $this->tmpFrame->setVariable($name, $symb->getType(), $symb->getSymbValue()) : throw new NonExistingFrameException("No temporary frame available");
            } else if ($scope === 'LF') {
                $this->currLocalFrame()->setVariable($name, $symb->getType(), $symb->getSymbValue());
            }
        }
    }

    public function getVariable(string $name): Variable {
        $scope = explode('@', $name)[0];
        $name = explode('@', $name)[1];

        if ($scope === 'GF') {
            return $this->globalFrame->getVariable($name);
        } else if ($scope === 'TF') {
            return $this->tmpFrame !== null ? $this->tmpFrame->getVariable($name) : throw new NonExistingFrameException("No temporary frame available");
        } else if ($scope === 'LF') {
            return $this->currLocalFrame()->getVariable($name);
        }
        else {
            throw new NonExistingFrameException("Just to satisfy the static analysis, this should never happen.");
        }
    }

    public function pushFrame(): void {
        // push temporary frame into local frames
        $this->tmpFrame !== null ? $this->localFrames[] = $this->tmpFrame : throw new NonExistingFrameException("No temporary frame available");
        $this->tmpFrame = null;
    }

    public function popFrame(): void {
        // pop top frame from local frames into temporary frame
        !empty($this->localFrames) ? $this->tmpFrame = array_pop($this->localFrames) : throw new NonExistingFrameException("No local frame available");
    }

    private function currLocalFrame(): Frame {
        return empty($this->localFrames) ? throw new NonExistingFrameException("No local frame available") : end($this->localFrames);
    }

    public function createTmpFrame(): void {
        $this->tmpFrame = new Frame();
    }
}