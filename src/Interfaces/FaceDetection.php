<?php

namespace HabibAlkhabbaz\IdentityDocuments\Interfaces;

use Intervention\Image\Image;
use HabibAlkhabbaz\IdentityDocuments\IdentityImage;

interface FaceDetection
{
    public function detect(IdentityImage $image): ?Image;
}
