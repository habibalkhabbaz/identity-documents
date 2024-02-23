<?php

namespace HabibAlkhabbaz\IdentityDocuments\Interfaces;

use Intervention\Image\Image;
use HabibAlkhabbaz\IdentityDocuments\Responses\OcrResponse;

interface Ocr
{
    public function ocr(Image $image): OcrResponse;
}
