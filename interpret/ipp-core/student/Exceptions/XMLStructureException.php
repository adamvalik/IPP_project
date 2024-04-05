<?php

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class XMLStructureException extends IPPException {
    public function __construct(string $message = "XML structure is invalid", int $code = 32, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
