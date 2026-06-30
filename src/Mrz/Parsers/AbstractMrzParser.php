<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers;

use HabibAlkhabbaz\IdentityDocuments\Mrz\Contracts\MrzParser;

abstract class AbstractMrzParser implements MrzParser
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
            'personal_number' => $this->personalNumber(),
            'optional_data_row_1' => $this->optionalDataRow1(),
            'optional_data_row_2' => $this->optionalDataRow2(),
        ];
    }

    /**
     * Split the machine-readable name field into a last name and a list of
     * given names. Returns an empty given-name list when the "<<" separator
     * is missing (e.g. partial OCR or an empty string) instead of failing.
     *
     * @return array{0: string, 1: array<int, string>}
     */
    protected function getFirstLastName(string $fullName): array
    {
        $parts = explode('<<', $fullName);

        $lastName = $parts[0];
        $firstName = $parts[1] ?? '';

        return [$lastName, explode('<', $firstName)];
    }

    protected function getFullCountryName(string $countryCode): ?string
    {
        $countryCode = str_replace('<', '', $countryCode);

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

    protected function personalNumber(): ?string
    {
        return null;
    }

    protected function optionalDataRow1(): ?string
    {
        return null;
    }

    protected function optionalDataRow2(): ?string
    {
        return null;
    }

    abstract protected function fullName(): string;

    abstract protected function documentNumber(): string;

    abstract protected function dateOfBirth(): string;

    abstract protected function sex(): string;

    abstract protected function expirationDate(): string;

    abstract protected function nationality(): string;
}
