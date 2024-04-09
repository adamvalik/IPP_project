<?php
/**
 * Project: IPP Interpreter
 * @author Adam Valík <xvalik05>
 */

namespace IPP\Student\Exceptions;

use Throwable;
use IPP\Core\Exception\IPPException;

class IPPCoreIntegrationException extends IPPException {
    public function __construct(string $message = "Integration error", int $code = 88, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
