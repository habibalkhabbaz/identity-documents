<?php

namespace DummyNamespace;

use HabibAlkhabbaz\IdentityDocuments\IdentityImage;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\FaceDetection;
use Intervention\Image\Image;

class DummyClass implements FaceDetection
{
    public function detect(IdentityImage $image): ?Image
    {
        // TODO: Add face detection and return image of face
        return new Image();
    }
}
