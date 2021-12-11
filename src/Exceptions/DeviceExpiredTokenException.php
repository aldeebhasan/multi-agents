<?php

namespace Aldeebhasan\MultiAgents\Exceptions;

use Throwable;

class DeviceExpiredTokenException extends \Exception
{

    public function __construct($message = "Unauthenticated Device")
    {
        parent::__construct($message);
    }
}
