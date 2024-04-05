<?php

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class OperandTypeException extends IPPException {
    public function __construct(string $message = "Operand type error", int $code = 53, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
