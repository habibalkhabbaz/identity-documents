<?php

namespace HabibAlkhabbaz\IdentityDocuments\Interfaces;

use HabibAlkhabbaz\IdentityDocuments\IdentityImage;
use Intervention\Image\Image;

interface FaceDetection
{
    public function detect(IdentityImage $image): ?Image;
}
