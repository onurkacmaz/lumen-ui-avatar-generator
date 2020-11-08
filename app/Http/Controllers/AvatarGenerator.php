<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AvatarGenerator
{
    public const BG_COLOR = "#ffffff";
    public const TXT_COLOR = "#ffffff";

    private $name;
    private $bgColor;
    private $width = 64;
    private $height = 64;
    private $fontSize = 15;
    private $textColor;

    public function setName(string $name): AvatarGenerator
    {
        $this->name = $name;
        return $this;
    }

    public function setBackgroundColor(string $colorCode = self::BG_COLOR): AvatarGenerator
    {
        $this->bgColor = $colorCode;
        return $this;
    }

    public function setSize(int $width, int $height): AvatarGenerator
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function setFontSize(string $fontSize): AvatarGenerator
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    public function setTextColor(string $textColor = self::TXT_COLOR): AvatarGenerator
    {
        $this->textColor = $textColor;
        return $this;
    }

    public function upperCase(): AvatarGenerator
    {
        $this->name = Str::upper($this->name);
        return $this;
    }

    public function lowerCase(): AvatarGenerator
    {
        $this->name = Str::lower($this->name);
        return $this;
    }

    public function generate(): string
    {
        $xAxis = $this->getWidth() / 2;
        $yAxis = $this->getHeight() / 2;

        $img = Image::canvas($this->getWidth(), $this->getHeight(), $this->getBgColor())->text($this->getName(), $xAxis, $yAxis, function ($font) {
                $font->file(app()->basePath("public/fonts/Roboto-Black.ttf"));
                $font->size($this->getFontSize());
                $font->color($this->getTextColor());
                $font->align('center');
                $font->valign('center');
            })->encode("png", 100);

        $mimeType = last(explode("/", $img->mime()));
        $name = uniqid('', false) . '.' . $mimeType;
        $destinationPath = app()->basePath("public/images/");

        $img->save($destinationPath . $name, 100);

        return "images/" . $name;
    }

    private function getName(): string
    {
        $words = explode(" ", $this->name);
        if (count($words) <= 1) {
            $this->name = Str::substr($words[0], 0, 1);
        } else {
            $this->name = Str::substr($words[0], 0, 1) . Str::substr(last($words), 0, 1);
        }
        return $this->name;
    }

    private function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    private function getWidth(): ?int
    {
        return $this->width;
    }

    private function getHeight(): ?int
    {
        return $this->height;
    }

    private function getFontSize(): ?int
    {
        return $this->fontSize;
    }

    private function getTextColor(): ?string
    {
        return $this->textColor;
    }

}
