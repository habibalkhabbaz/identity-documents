<?php

namespace HabibAlkhabbaz\IdentityDocuments\Enums;

use HabibAlkhabbaz\IdentityDocuments\Mrz\Contracts\MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\MrvaMrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\MrvbMrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\NullMrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument1MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument2MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument3MrzParser;

enum IdentityDocumentType: int
{
    case TravelDocument1 = 1;
    case TravelDocument2 = 2;
    case TravelDocument3 = 3;
    case Mrva = 4;
    case Mrvb = 5;
    case Null = 6;

    public function checks(): array
    {
        // hash position => [field ranges to validate]
        return match ($this) {
            self::TravelDocument1 => [
                14 => [[5, 9]], // document number
                36 => [[30, 6]], // date of birth
                44 => [[38, 6]], // expiry date
                59 => [[5, 10], [30, 7], [38, 7], [48, 11]],
            ],
            self::TravelDocument2 => [
                45 => [[36, 9]], // document number
                55 => [[49, 6]], // date of birth
                63 => [[57, 6]], // expiry date
                71 => [[36, 10], [49, 7], [57, 7], [64, 7]], // document number & hash - expiry date & hash - expiry date & hash
            ],
            self::TravelDocument3 => [
                53 => [[44, 9]], // document number
                63 => [[57, 6]], // date of birth
                71 => [[65, 6]], // expiry date
                86 => [[72, 14]], // personal number
                87 => [[44, 10], [57, 7], [65, 7], [72, 15]],
            ],
            self::Mrvb => [
                45 => [[36, 9]], // document number
                55 => [[49, 6]], // date of birth
                63 => [[57, 6]], // expiry date
            ],
            self::Mrva => [
                53 => [[44, 9]], // document number
                63 => [[57, 6]], // date of birth
                71 => [[65, 6]], // expiry date
            ],
        };
    }

    public function length(): int
    {
        return match ($this) {
            self::TravelDocument1 => 90,
            self::TravelDocument2, self::Mrvb => 72,
            self::TravelDocument3, self::Mrva => 88,
        };
    }

    public function parser(): MrzParser
    {
        return match ($this) {
            self::TravelDocument1 => new TravelDocument1MrzParser(),
            self::TravelDocument2 => new TravelDocument2MrzParser(),
            self::TravelDocument3 => new TravelDocument3MrzParser(),
            self::Mrva => new MrvaMrzParser(),
            self::Mrvb => new MrvbMrzParser(),
            self::Null => new NullMrzParser(),
        };
    }
}
