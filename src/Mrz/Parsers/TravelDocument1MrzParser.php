<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

class TravelDocument1MrzParser extends AbstractMrzParser
{
    protected function fullName(): string
    {
        return substr($this->text, 60, 30);
    }

    protected function documentNumber(): string
    {
        return rtrim(substr($this->text, 5, 9), '<');
    }

    protected function dateOfBirth(): string
    {
        return substr($this->text, 30, 6);
    }

    protected function sex(): string
    {
        return substr($this->text, 37, 1);
    }

    protected function expirationDate(): string
    {
        return substr($this->text, 38, 6);
    }

    protected function nationality(): string
    {
        return substr($this->text, 45, 3);
    }

    protected function optionalDataRow1(): ?string
    {
        return substr($this->text, 15, 15);
    }

    protected function optionalDataRow2(): ?string
    {
        return substr($this->text, 48, 11);
    }
}
