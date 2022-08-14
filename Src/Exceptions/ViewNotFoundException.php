<?php
/**
 * Created by (c)danidoble 2022.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\Routing\Exceptions;

use RuntimeException;
use Throwable;

class ViewNotFoundException extends RuntimeException
{
    public function __construct(string $message = "", string $replace = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = str_replace('%s', $replace, $message);
        parent::__construct($message, $code, $previous);
    }
}
