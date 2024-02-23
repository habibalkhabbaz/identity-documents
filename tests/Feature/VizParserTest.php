<?php

namespace HabibAlkhabbaz\IdentityDocuments\Tests\Feature;


use HabibAlkhabbaz\IdentityDocuments\Tests\TestCase;
use HabibAlkhabbaz\IdentityDocuments\Viz\VizParser;

class VizParserTest extends TestCase
{
    private string $mrz = 'P<NLDDE<BRUIJN<<WILLEKE<LISELOTTE<<<<<<<<<<<SPECI20142NLD6503101F2401151999999990<<<<<82';
    private string $full_text = 'PASPOORT PASSPORT PASSEPORT O KONINKRIJK DER NEDERLANDEN KINGDOM OF THE NETHERLANDS ROYAUME DES PAYSBAS P NLD Nederlandse SPECI2014 De Bruijn e/v Molenaar Willeke Liselotte 10 MAA/MAR 1965  Specimen V/F 1,75 m dm ve wwwd 15 JAN/JAN 2014 15 JAN/JAN 2024 1935 Burg. van Stad en Dorp w.L. de 3ujn P<NLDDE<BRUIJN<<WILLEKE<LISELOTTE<<<<<<<<<<< SPECI20142NLD6503101F2401151999999990<<<<<82';
    private array $parsed = [
        'document_key' => 'P',
        'document_type' => '<',
        'issuing_country' => 'NLD',
        'full_name' => 'DE<BRUIJN<<WILLEKE<LISELOTTE<<<<<<<<<<<',
        'document_number' => 'SPECI2014',
        'nationality' => 'NLD',
        'date_of_birth' => '650310',
        'sex' => 'F',
        'expiration_date' => '240115',
        'personal_number' => '999999990<<<<<',
        'optional_data_row_1' => null,
        'optional_data_row_2' => null,
        'last_name' => 'DE<BRUIJN',
        'first_name' => [
            0 => 'WILLEKE',
            1 => 'LISELOTTE',
        ],
        'issuing_country_name' => 'Netherlands',
        'nationality_name' => 'Netherlands',
    ];
    private array $expected = [
        'first_name' => [
            [
                'value' => 'Willeke',
                'confidence' => 1
            ],
            [
                'value' => 'Liselotte',
                'confidence' => 1
            ],
        ],
        'last_name' => [
            'value' => 'De Bruijn',
            'confidence' => 1
        ],
        'document_number' => [
            'value' => 'SPECI2014'
        ]
    ];

    /** @test */
    public function viz_is_parsed_correctly()
    {
        $parser = new VizParser();
        $this->assertEquals($this->expected, $parser->match($this->parsed, $this->mrz, $this->full_text));
    }
}
