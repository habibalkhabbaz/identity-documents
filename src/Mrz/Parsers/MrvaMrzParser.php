<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

use HabibAlkhabbaz\IdentityDocuments\Mrz\Contracts\MrzParser;

class MrvaMrzParser implements MrzParser
{
    protected string $text;

    public function parse(?string $text): array
    {
        $this->text = $text ?? '';

        $fullName = $this->fullName();

        [$lastName, $firstName] = $this->getFirstLastName($fullName);

        $issuingCountry = $this->issuingCountry();
        $nationality = $this->nationality();

        return [
            'document_key' => $this->documentKey(),
            'document_type' => $this->documentType(),
            'document_number' => $this->documentNumber(),
            'issuing_country' => $issuingCountry,
            'issuing_country_name' => $this->getFullCountryName($issuingCountry),
            'last_name' => $lastName,
            'first_name' => $firstName,
            'full_name' => $fullName,
            'nationality' => $nationality,
            'nationality_name' => $this->getFullCountryName($nationality),
            'date_of_birth' => $this->dateOfBirth(),
            'sex' => $this->sex(),
            'expiration_date' => $this->expirationDate(),
            'personal_number' => null,
            'optional_data_row_1' => null,
            'optional_data_row_2' => $this->optionalDataRow2(),
        ];
    }

    private function getFirstLastName(string $fullName): array
    {
        [$lastName, $firstName] = explode('<<', $fullName);

        return [$lastName, explode('<', $firstName)];
    }

    private function getFullCountryName($countryCode)
    {
        $countryCode = preg_replace('/</', '', $countryCode);

        return config('countries')[$countryCode] ?? null;
    }

    protected function documentKey(): string
    {
        return substr($this->text, 0, 1);
    }

    protected function documentType(): string
    {
        return substr($this->text, 1, 1);
    }

    protected function issuingCountry(): string
    {
        return substr($this->text, 2, 3);
    }

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

    protected function optionalDataRow2(): string
    {
        return substr($this->text, 72, 16);
    }
}
