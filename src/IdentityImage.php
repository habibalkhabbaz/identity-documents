<?php

namespace HabibAlkhabbaz\IdentityDocuments;

use Exception;
use HabibAlkhabbaz\IdentityDocuments\Exceptions\CouldNotSetService;
use HabibAlkhabbaz\IdentityDocuments\Filters\MergeFilter;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\FaceDetection;
use HabibAlkhabbaz\IdentityDocuments\Interfaces\Ocr;
use Intervention\Image\Image;
use ReflectionClass;

class IdentityImage
{
    public Image $image;

    public Exception $error;

    public string $text;

    public ?Image $face;

    private string $ocrService;

    private string $faceDetectionService;

    public function __construct(Image $image, $ocrService, $faceDetectionService)
    {
        $this->setOcrService($ocrService);
        $this->setFaceDetectionService($faceDetectionService);
        $this->setImage($image);
    }

    public function setOcrService(string $service)
    {
        $class = new ReflectionClass($service);
        if (! $class->implementsInterface(Ocr::class)) {
            throw CouldNotSetService::couldNotDetectInterface(Ocr::class, $service);
        }

        $this->ocrService = $service;
    }

    public function setFaceDetectionService(string $service)
    {
        $class = new ReflectionClass($service);
        if (! $class->implementsInterface(FaceDetection::class)) {
            throw CouldNotSetService::couldNotDetectInterface(FaceDetection::class, $service);
        }

        $this->faceDetectionService = $service;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function merge(IdentityImage $image): IdentityImage
    {
        return new IdentityImage($this->image->filter(new MergeFilter($image->image)), $this->ocrService, $this->faceDetectionService);
    }

    public function ocr(): string
    {
        /** @var Ocr $service */
        $service = new $this->ocrService();

        return $this->text = $service->ocr($this->image)->text;
    }

    public function face(): ?Image
    {
        /** @var FaceDetection $service */
        $service = new $this->faceDetectionService();

        return $this->face = $service->detect($this);
    }
}
