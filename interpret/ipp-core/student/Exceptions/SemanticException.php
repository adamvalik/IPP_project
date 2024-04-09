<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class SemanticException extends IPPException {
    public function __construct(string $message = "Semantic error", int $code = 52, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
