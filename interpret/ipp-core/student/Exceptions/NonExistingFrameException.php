<?php

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class NonExistingFrameException extends IPPException {
    public function __construct(string $message = "Non-existing frame error", int $code = 55, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
