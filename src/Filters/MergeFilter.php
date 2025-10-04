<?php

namespace HabibAlkhabbaz\IdentityDocuments\Filters;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class MergeFilter implements ModifierInterface
{
    /**
     * Size of filter effects.
     */
    private ImageInterface $addImg;

    /**
     * Creates new instance of filter.
     */
    public function __construct(ImageInterface $image)
    {
        $this->addImg = $image;
    }

    /**
     * Applies filter effects to given image.
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $baseImgX = $image->width();
        $baseImgY = $image->height();

        $addImgX = $this->addImg->width();
        $addImgY = $this->addImg->height();

        $canvasY = $baseImgY + $addImgY;
        $canvasX = ($baseImgX > $addImgX) ? $baseImgX : $addImgX;

        $canvas = ImageManager::gd()->create($canvasX, $canvasY);
        $canvas->place($image);
        $canvas->place($this->addImg, 'top-left', 0, $baseImgY);

        return $canvas;
    }
}
