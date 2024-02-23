<?php

namespace HabibAlkhabbaz\IdentityDocuments\Exceptions;

use Exception;

class CouldNotSetService extends Exception
{
    public static function couldNotDetectInterface($interface, $service)
    {
        return new self("Could not detect interface {$interface} on service {$service}.");
    }
}
