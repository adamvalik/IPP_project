<?php

namespace IPP\Student;

class DataStack {
    private $stack = [];

    public function push($item) {
        array_push($this->stack, $item);
    }

    public function pop() {
        if ($this->isEmpty()) {
            //throw new Exception("DataStack is empty. Cannot pop.");
        }
        return array_pop($this->stack);
    }

    public function top() {
        if ($this->isEmpty()) {
            //throw new Exception("DataStack is empty. Cannot read top element.");
        }
        return end($this->stack);
    }

    public function isEmpty() {
        return empty($this->stack);
    }

    public function clear() {
        $this->stack = [];
    }

    public function size() {
        return count($this->stack);
    }
}
