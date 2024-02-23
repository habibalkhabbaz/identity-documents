<?php

namespace DummyNamespace;

use HabibAlkhabbaz\IdentityDocuments\Interfaces\Ocr;
use HabibAlkhabbaz\IdentityDocuments\Responses\OcrResponse;
use Intervention\Image\Image;

class DummyClass implements Ocr
{
    public function ocr(Image $image): OcrResponse
    {
        // TODO: Add OCR and return text
        return new OcrResponse('Response text');
    }
}
