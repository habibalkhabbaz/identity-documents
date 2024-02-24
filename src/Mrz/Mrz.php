<?php

namespace HabibAlkhabbaz\IdentityDocuments\Mrz;

use HabibAlkhabbaz\IdentityDocuments\Enums\IdentityDocumentType;

class Mrz
{
    /** @var array<string, array<IdentityDocumentType>> */
    protected array $typesByKeys;

    public IdentityDocumentType $type = IdentityDocumentType::Null;

    public function __construct()
    {
        $this->typesByKeys = [
            'P' => [IdentityDocumentType::TravelDocument3],
            'I' => [IdentityDocumentType::TravelDocument1, IdentityDocumentType::TravelDocument2],
            'A' => [IdentityDocumentType::TravelDocument1, IdentityDocumentType::TravelDocument2],
            'C' => [IdentityDocumentType::TravelDocument1, IdentityDocumentType::TravelDocument2],
            'V' => [IdentityDocumentType::Mrva, IdentityDocumentType::Mrvb],
        ];
    }
}
