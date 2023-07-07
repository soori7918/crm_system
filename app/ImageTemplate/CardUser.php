<?php

namespace App\ImageTemplate;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class CardUser implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(200, 150);
    }
}
