<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class MissingValueException extends IPPException {
    public function __construct(string $message = "Missing value error", int $code = 56, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
