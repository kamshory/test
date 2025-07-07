<?php

namespace Sipro\Exception;
use Exception;
/**
 * RegressionException is thrown when there is an error in regression calculations.
 *
 * This exception is used to indicate issues such as division by zero, invalid data lengths,
 * or untrained models in regression analysis.
 */

class RegressionException extends Exception
{
    /**
     * Constructs a new RegressionException.
     *
     * @param string $message The error message.
     * @param int $code The error code (optional).
     * @param Exception|null $previous The previous exception (optional).
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}