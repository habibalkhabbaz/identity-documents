<?php

namespace HabibAlkhabbaz\IdentityDocuments\Interfaces;

use HabibAlkhabbaz\IdentityDocuments\Responses\OcrResponse;
use Intervention\Image\Interfaces\ImageInterface;

interface Ocr
{
    public function ocr(ImageInterface $image): OcrResponse;
}
