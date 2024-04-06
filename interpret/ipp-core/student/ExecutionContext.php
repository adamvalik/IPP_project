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
        $this->tmpFrame = new Frame();
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

    public function set_variable(string $var): void {
        $scope = explode('@', $var)[0];
        $name = explode('@', $var)[1];

        if ($scope === 'GF') {
            $this->globalFrame->setVariable($name);
        } else if ($scope === 'TF') {
            $this->tmpFrame !== null ? $this->tmpFrame->setVariable($name) : throw new NonExistingFrameException("No temporary frame available");
        } else if ($scope === 'LF') {
            $this->currLocalFrame()->setVariable($name);
        }
    }

    public function get_variable(string $name): Variable {
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
            throw new NonExistingFrameException("Just to satisfy the linter, this should never happen.");
        }
    }

    public function pushFrame(): void {
        // push temporary frame into local frames
        $this->tmpFrame !== null ? array_push($this->localFrames, $this->tmpFrame) : throw new NonExistingFrameException("No temporary frame available");
        $this->tmpFrame = null;
    }

    public function popFrame(): void {
        // pop top frame from local frames into temporary frame
        !empty($this->localFrames) ? $this->tmpFrame = array_pop($this->localFrames) : throw new NonExistingFrameException("No local frame available");
    }

    public function currLocalFrame(): Frame {
        return empty($this->localFrames) ? throw new NonExistingFrameException("No local frame available") : end($this->localFrames);
    }

    public function createTmpFrame(): void {
        $this->tmpFrame = new Frame();
    }
}