<?php

use HabibAlkhabbaz\IdentityDocuments\Services\Google;

return [
    'ocr_service' => Google::class,
    'face_detection_service' => Google::class,
    'merge_images' => false, // bool
];
