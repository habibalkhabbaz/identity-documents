<?php

namespace HabibAlkhabbaz\IdentityDocuments\Facades;

use HabibAlkhabbaz\IdentityDocuments\IdentityDocument;
use Illuminate\Support\Facades\Facade;

class IdentityDocuments extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return IdentityDocument::class;
    }
}
