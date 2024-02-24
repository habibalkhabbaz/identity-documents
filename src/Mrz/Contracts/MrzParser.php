<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Contracts;

interface MrzParser
{
    public function parse(?string $text): array;
}
