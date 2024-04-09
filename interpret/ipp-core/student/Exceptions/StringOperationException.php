<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class StringOperationException extends IPPException {
    public function __construct(string $message = "String operation error", int $code = 58, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}