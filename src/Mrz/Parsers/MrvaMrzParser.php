<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

class MrvaMrzParser extends AbstractMrzParser
{
    protected function fullName(): string
    {
        return substr($this->text, 5, 39);
    }

    protected function documentNumber(): string
    {
        return rtrim(substr($this->text, 44, 9), '<');
    }

    protected function dateOfBirth(): string
    {
        return substr($this->text, 57, 6);
    }

    protected function sex(): string
    {
        return substr($this->text, 64, 1);
    }

    protected function expirationDate(): string
    {
        return substr($this->text, 65, 6);
    }

    protected function nationality(): string
    {
        return substr($this->text, 54, 3);
    }

    protected function optionalDataRow2(): ?string
    {
        return substr($this->text, 72, 16);
    }
}
