<?php

namespace DummyNamespace;

use Intervention\Image\Image;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\Ocr;
use HabibAlkhabbaz\IdentityDocuments\Responses\OcrResponse;

class DummyClass implements Ocr
{
    public function ocr(Image $image): OcrResponse
    {
        // TODO: Add OCR and return text
        return new OcrResponse('Response text');
    }
}
