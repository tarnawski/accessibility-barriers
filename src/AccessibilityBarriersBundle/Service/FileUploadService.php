<?php

namespace AccessibilityBarriersBundle\Service;

class FileUploadService
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function base64Decode($base64)
    {
        return base64_decode($base64);
    }

    public function upload($originalFileName, $image)
    {
        $originalFilePath = sprintf('%s/%s', $this->uploadDir, $originalFileName);

        return file_put_contents($originalFilePath, $image) == false ? false : true;
    }

    public function generateThumb($originalFileName, $thumbnailFileName)
    {
        $sourceImagePath = sprintf('%s/%s', $this->uploadDir, $originalFileName);
        $thumbnailImagePath = sprintf('%s/%s', $this->uploadDir, $thumbnailFileName);

        list($sourceImageWidth, $sourceImageHeight) = getimagesize($sourceImagePath);

        $ratio = $sourceImageWidth / 200;
        $maxImageHeight = $sourceImageHeight / $ratio;
        $maxImageWidth = $sourceImageWidth / $ratio;


        $sourceGdImage = imagecreatefromjpeg($sourceImagePath);
        if ($sourceGdImage === false) {
            return false;
        }
        $source_aspect_ratio = $sourceImageWidth / $sourceImageHeight;
        $thumbnail_aspect_ratio = $maxImageWidth / $maxImageHeight;
        if ($sourceImageWidth <= $maxImageWidth && $sourceImageHeight <= $maxImageHeight) {
            $thumbnailImageWidth = $sourceImageWidth;
            $thumbnailImageHeight = $sourceImageHeight;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnailImageWidth = (int)($maxImageHeight * $source_aspect_ratio);
            $thumbnailImageHeight = $maxImageHeight;
        } else {
            $thumbnailImageWidth = $maxImageWidth;
            $thumbnailImageHeight = (int)($maxImageWidth / $source_aspect_ratio);
        }
        $thumbnailGdImage = imagecreatetruecolor($thumbnailImageWidth, $thumbnailImageHeight);
        imagecopyresampled($thumbnailGdImage, $sourceGdImage, 0, 0, 0, 0, $thumbnailImageWidth, $thumbnailImageHeight, $sourceImageWidth, $sourceImageHeight);
        imagejpeg($thumbnailGdImage, $thumbnailImagePath);
        imagedestroy($sourceGdImage);
        imagedestroy($thumbnailGdImage);

        return true;
    }
}
