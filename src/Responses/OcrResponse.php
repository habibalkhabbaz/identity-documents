<?php

namespace HabibAlkhabbaz\IdentityDocuments\Responses;

class OcrResponse
{
    public string $text;

    public function __construct(string $input)
    {
        $this->text = $input;
    }
}
