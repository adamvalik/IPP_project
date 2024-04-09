<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class OperandValueException extends IPPException {
    public function __construct(string $message = "Operand value error", int $code = 57, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
