<?php

namespace Aldeebhasan\MultiAgents\Exceptions;

use Throwable;

class DeviceAuthenticationException extends \Exception
{

    public function __construct($message = "Unauthenticated Device")
    {
        parent::__construct($message);
    }
}
