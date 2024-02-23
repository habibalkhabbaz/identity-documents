<?php

namespace HabibAlkhabbaz\IdentityDocuments\Filters;

use Intervention\Image\Facades\Image as Img;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class MergeFilter implements FilterInterface
{
    /**
     * Size of filter effects.
     */
    private Image $addImg;

    /**
     * Creates new instance of filter.
     */
    public function __construct(Image $image)
    {
        $this->addImg = $image;
    }

    /**
     * Applies filter effects to given image.
     */
    public function applyFilter(Image $image): Image
    {
        $baseImgX = $image->width();
        $baseImgY = $image->height();

        $addImgX = $this->addImg->width();
        $addImgY = $this->addImg->height();

        $canvasY = $baseImgY + $addImgY;
        $canvasX = ($baseImgX > $addImgX) ? $baseImgX : $addImgX;

        $canvas = Img::canvas($canvasX, $canvasY, '#ffffff');
        $canvas->insert($image);
        $canvas->insert($this->addImg, 'top-left', 0, $baseImgY);

        return $canvas;
    }
}
