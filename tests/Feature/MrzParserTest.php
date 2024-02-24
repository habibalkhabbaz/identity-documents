<?php

namespace HabibAlkhabbaz\IdentityDocuments\Tests\Feature;

use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\MrvaMrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\MrvbMrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument1MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument2MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\Parsers\TravelDocument3MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Tests\TestCase;

class MrzParserTest extends TestCase
{
    public function test_correctly_parse_mrz_for_td3()
    {
        $mrz = 'P<NLDDE<BRUIJN<<WILLEKE<LISELOTTE<<<<<<<<<<<SPECI20142NLD6503101F2401151999999990<<<<<82';

        $parser = new TravelDocument3MrzParser();
        $this->assertEquals([
            'document_key' => 'P',
            'document_type' => '<',
            'issuing_country' => 'NLD',
            'full_name' => 'DE<BRUIJN<<WILLEKE<LISELOTTE<<<<<<<<<<<',
            'document_number' => 'SPECI2014',
            'nationality' => 'NLD',
            'date_of_birth' => '650310',
            'sex' => 'F',
            'expiration_date' => '240115',
            'personal_number' => '999999990',
            'optional_data_row_1' => null,
            'optional_data_row_2' => '999999990<<<<<',
            'last_name' => 'DE<BRUIJN',
            'first_name' => [
                0 => 'WILLEKE',
                1 => 'LISELOTTE',
            ],
            'issuing_country_name' => 'Netherlands',
            'nationality_name' => 'Netherlands',
        ], $parser->parse($mrz));
    }

    public function test_correctly_parse_mrz_for_td2()
    {
        $mrz = 'I<UTOERIKSSON<<ANNA<MARIA<<<<<<<<<<<D231458907UTO7408122F1204159<<<<<<<6';

        $parser = new TravelDocument2MrzParser();
        $this->assertEquals([
            'document_key' => 'I',
            'document_type' => '<',
            'issuing_country' => 'UTO',
            'full_name' => 'ERIKSSON<<ANNA<MARIA<<<<<<<<<<<',
            'document_number' => 'D23145890',
            'nationality' => 'UTO',
            'date_of_birth' => '740812',
            'sex' => 'F',
            'expiration_date' => '120415',
            'personal_number' => null,
            'optional_data_row_1' => null,
            'optional_data_row_2' => '<<<<<<<',
            'last_name' => 'ERIKSSON',
            'first_name' => [
                0 => 'ANNA',
                1 => 'MARIA',
            ],
            'issuing_country_name' => null,
            'nationality_name' => null,
        ], $parser->parse($mrz));
    }

    public function test_correctly_parse_mrz_for_td1()
    {
        $mrz = 'I<SWE59000002<8198703142391<<<8703145M1701027SWE<<<<<<<<<<<8SPECIMEN<<SVEN<<<<<<<<<<<<<<<<';

        $parser = new TravelDocument1MrzParser();
        $this->assertEquals([
            'document_key' => 'I',
            'document_type' => '<',
            'issuing_country' => 'SWE',
            'full_name' => 'SPECIMEN<<SVEN<<<<<<<<<<<<<<<<',
            'document_number' => '59000002',
            'nationality' => 'SWE',
            'date_of_birth' => '870314',
            'sex' => 'M',
            'expiration_date' => '170102',
            'personal_number' => null,
            'optional_data_row_1' => '198703142391<<<',
            'optional_data_row_2' => '<<<<<<<<<<<',
            'last_name' => 'SPECIMEN',
            'first_name' => [
                0 => 'SVEN',
            ],
            'issuing_country_name' => 'Sweden',
            'nationality_name' => 'Sweden',
        ], $parser->parse($mrz));
    }

    public function test_correctly_parse_mrz_for_mrva()
    {
        $mrz = 'V<UTOERIKSSON<<ANNA<MARIA<<<<<<<<<<<<<<<<<<<L8988901C4XXX4009078F96121096ZE184226B<<<<<<';

        $parser = new MrvaMrzParser();
        $this->assertEquals([
            'document_key' => 'V',
            'document_type' => '<',
            'issuing_country' => 'UTO',
            'full_name' => 'ERIKSSON<<ANNA<MARIA<<<<<<<<<<<<<<<<<<<',
            'document_number' => 'L8988901C',
            'nationality' => 'XXX',
            'date_of_birth' => '400907',
            'sex' => 'F',
            'expiration_date' => '961210',
            'personal_number' => null,
            'optional_data_row_1' => null,
            'optional_data_row_2' => '6ZE184226B<<<<<<',
            'last_name' => 'ERIKSSON',
            'first_name' => [
                0 => 'ANNA',
                1 => 'MARIA',
            ],
            'issuing_country_name' => null,
            'nationality_name' => 'Unspecified nationality',
        ], $parser->parse($mrz));
    }

    public function test_correctly_parse_mrz_for_mrvb()
    {
        $mrz = 'V<UTOERIKSSON<<ANNA<MARIA<<<<<<<<<<<L8988901C4XXX4009078F9612109<<<<<<<<';

        $parser = new MrvbMrzParser();
        $this->assertEquals([
            'document_key' => 'V',
            'document_type' => '<',
            'issuing_country' => 'UTO',
            'full_name' => 'ERIKSSON<<ANNA<MARIA<<<<<<<<<<<',
            'document_number' => 'L8988901C',
            'nationality' => 'XXX',
            'date_of_birth' => '400907',
            'sex' => 'F',
            'expiration_date' => '961210',
            'personal_number' => null,
            'optional_data_row_1' => null,
            'optional_data_row_2' => '<<<<<<<<',
            'last_name' => 'ERIKSSON',
            'first_name' => [
                0 => 'ANNA',
                1 => 'MARIA',
            ],
            'issuing_country_name' => null,
            'nationality_name' => 'Unspecified nationality',
        ], $parser->parse($mrz));
    }
}
