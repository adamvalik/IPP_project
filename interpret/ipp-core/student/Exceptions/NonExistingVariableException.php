<?php

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class NonExistingVariableException extends IPPException {
    public function __construct(string $message = "Non-existing variable error", int $code = 54, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
