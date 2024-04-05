<?php

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class XMLFormatException extends IPPException {
    public function __construct(string $message = "XML format is invalid", int $code = 31, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
