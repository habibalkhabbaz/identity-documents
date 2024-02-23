<?php

namespace DummyNamespace;

use HabibAlkhabbaz\IdentityDocuments\IdentityImage;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\FaceDetection;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\Ocr;
use HabibAlkhabbaz\IdentityDocuments\Responses\OcrResponse;
use Intervention\Image\Image;

class DummyClass implements FaceDetection, Ocr
{
    public function ocr(Image $image): OcrResponse
    {
        // TODO: Add OCR and return text
        return new OcrResponse('Response text');
    }

    public function detect(IdentityImage $image): ?Image
    {
        // TODO: Add face detection and return image of face
        return new Image();
    }
}
