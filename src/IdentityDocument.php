<?php

namespace HabibAlkhabbaz\IdentityDocuments;

use HabibAlkhabbaz\IdentityDocuments\Mrz\MrzParser;
use HabibAlkhabbaz\IdentityDocuments\Mrz\MrzSearcher;
use HabibAlkhabbaz\IdentityDocuments\Services\Google;
use HabibAlkhabbaz\IdentityDocuments\Viz\VizParser;
use Intervention\Image\Facades\Image as Img;
use Intervention\Image\Image;

class IdentityDocument
{
    public string $mrz;

    public array $viz;

    public string $type;

    public ?Image $face;

    public array $parsedMrz;

    private ?IdentityImage $frontImage;

    private ?IdentityImage $backImage;

    /** @var array<IdentityImage> */
    private array $images;

    private VizParser $resolver;

    private MrzSearcher $searcher;

    private MrzParser $parser;

    private string $ocrService;

    private string $faceDetectionService;

    private string $text = '';

    public function __construct($frontImage = null, $backImage = null)
    {
        $this->ocrService = config('identity_documents.ocr_service') ?? Google::class;
        $this->faceDetectionService = config('identity_documents.face_detection_service') ?? Google::class;

        if ($frontImage) {
            $this->addFrontImage($frontImage);
        }

        if ($backImage) {
            $this->addBackImage($backImage);
        }

        $this->searcher = new MrzSearcher();
        $this->parser = new MrzParser();
        $this->resolver = new VizParser();
    }

    public static function all($frontImage = null, $backImage = null): array
    {
        $id = new IdentityDocument($frontImage, $backImage);

        if (config('identity_documents.merge_images')) {
            $id->mergeBackAndFrontImages();
        }

        $mrz = $id->getMrz();
        $parsed = $id->getParsedMrz();
        $face = $id->getFace();

        $faceB64 = ($face) ?
            'data:image/jpg;base64,'.
            base64_encode(
                $face
                    ->resize(null, 200, fn ($constraint) => $constraint->aspectRatio())
                    ->encode()
                    ->encoded
            ) :
            null;
        $viz = $id->getViz();

        return [
            'type' => $id->type,
            'mrz' => $mrz,
            'parsed' => $parsed,
            'viz' => $viz,
            'face' => $faceB64,
        ];
    }

    public function addFrontImage($image): IdentityDocument
    {
        $this->frontImage = $this->createImage($image);
        $this->images[] = &$this->frontImage;

        return $this;
    }

    public function addBackImage($image): IdentityDocument
    {
        $this->backImage = $this->createImage($image);
        $this->images[] = &$this->backImage;

        return $this;
    }

    private function createImage($file): IdentityImage
    {
        return new IdentityImage(Img::make($file), $this->ocrService, $this->faceDetectionService);
    }

    public function setOcrService(string $service)
    {
        $this->ocrService = $service;

        foreach ($this->images as $image) {
            $image->setOcrService($service);
        }
    }

    public function setFaceDetectionService(string $service)
    {
        $this->faceDetectionService = $service;
        foreach ($this->images as $image) {
            $image->setFaceDetectionService($service);
        }
    }

    public function mergeBackAndFrontImages(): bool
    {
        if (! $this->frontImage || ! $this->backImage) {
            return false;
        }

        if (! $mergedImage = $this->frontImage->merge($this->backImage)) {
            return false;
        }

        $this->images = [&$mergedImage];

        return true;
    }

    private function mrz(): string
    {
        $this->mrz = '';

        foreach ($this->images as $image) {
            $this->text .= $image->ocr();
            if ($mrz = $this->searcher->search($image->text)) {
                $this->mrz = $mrz;
            }
        }

        $this->type = $this->searcher->type;

        return $this->mrz;
    }

    private function viz(): ?array
    {
        if (! $this->text) {
            return null;
        }

        return $this->viz = $this->resolver->match($this->parsedMrz, $this->mrz, $this->text);
    }

    public function getViz(): array
    {
        if (! isset($this->viz)) {
            return $this->viz();
        }

        return $this->viz;
    }

    public function getMrz(): string
    {
        if (! isset($this->mrz)) {
            return $this->mrz();
        }

        return $this->mrz;
    }

    public function getFace(): ?Image
    {
        if (! isset($this->face) || ! $this->face) {
            return $this->face();
        }

        return $this->face;
    }

    private function face(): ?Image
    {
        $this->face = null;

        foreach ($this->images as $image) {
            if ($face = $image->face()) {
                $this->face = $face;
                break;
            }
        }

        return $this->face;
    }

    public function getParsedMrz(): array
    {
        if (! isset($this->parsedMrz) || ! $this->mrz) {
            return $this->parseMrz();
        }

        return $this->parsedMrz;
    }

    public function setMrz($mrz): IdentityDocument
    {
        $this->mrz = $mrz;

        return $this;
    }

    private function parseMrz(): array
    {
        return $this->parsedMrz = $this->parser->parse($this->getMrz(), $this->type);
    }
}
