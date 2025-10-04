<?php

namespace HabibAlkhabbaz\IdentityDocuments\Interfaces;

use HabibAlkhabbaz\IdentityDocuments\IdentityImage;
use Intervention\Image\Interfaces\ImageInterface;

interface FaceDetection
{
    public function detect(IdentityImage $image): ?ImageInterface;
}
