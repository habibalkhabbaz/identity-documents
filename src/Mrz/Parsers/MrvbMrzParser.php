<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

class MrvbMrzParser extends AbstractMrzParser
{
    protected function fullName(): string
    {
        return substr($this->text, 5, 31);
    }

    protected function documentNumber(): string
    {
        return rtrim(substr($this->text, 36, 9), '<');
    }

    protected function dateOfBirth(): string
    {
        return substr($this->text, 49, 6);
    }

    protected function sex(): string
    {
        return substr($this->text, 56, 1);
    }

    protected function expirationDate(): string
    {
        return substr($this->text, 57, 6);
    }

    protected function nationality(): string
    {
        return substr($this->text, 46, 3);
    }

    protected function optionalDataRow2(): ?string
    {
        return substr($this->text, 64, 8);
    }
}
