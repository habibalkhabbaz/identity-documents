<?php

namespace DummyNamespace;

use Intervention\Image\Image;
use HabibAlkhabbaz\IdentityDocuments\IdentityImage;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\FaceDetection;

class DummyClass implements FaceDetection
{
    public function detect(IdentityImage $image): ?Image
    {
        // TODO: Add face detection and return image of face
        return new Image();
    }
}
