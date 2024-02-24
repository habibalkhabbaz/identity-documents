<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

use HabibAlkhabbaz\IdentityDocuments\Mrz\Contracts\MrzParser;

class NullMrzParser implements MrzParser
{
    public function parse(?string $text): array
    {
        return [];
    }
}
