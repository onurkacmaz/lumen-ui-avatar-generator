<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class AvatarGenerator
{
    public const BG_COLOR = "#fafbfb";
    public const TXT_COLOR = "#000000";

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

    public function setSize(int $width = null, int $height = null): AvatarGenerator
    {
        $this->width = $width ?: $this->width;
        $this->height = $height ?: $this->height;
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

    public function generate(): array
    {
        $xAxis = $this->getWidth() / 2;
        $yAxis = $this->getHeight() / 2;

        try {

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

            $res = [
                "status" => 200,
                "url" => "images/" . $name
            ];

        }catch (Exception $e) {
            if ($e instanceof NotReadableException) {
                $errors = [
                    "not_readable_color" => $e->getMessage()
                ];
            }else {
                $errors = [
                    "unknown_error" => $e->getMessage()
                ];
            }
            $res = [
                "status" => 400,
                "errors" => $errors
            ];
        }

        return $res;
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
