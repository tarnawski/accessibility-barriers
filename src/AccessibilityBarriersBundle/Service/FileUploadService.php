<?php

namespace AccessibilityBarriersBundle\Service;

class FileUploadService
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload($base64)
    {
        $parts = explode(',', $base64);
        $image = base64_decode($parts[1]);

        $fileName = md5(uniqid());
        $fileType = $this->getImageType($image);
        $fullFileName = sprintf('%s.%s', $fileName, $fileType);
        $path = sprintf('%s/%s', $this->uploadDir, $fullFileName);
        file_put_contents($path, $image);

        return $fullFileName;
    }

    public function getImageType($imageData)
    {
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
        $split = explode( '/', $mime_type );
        $type = $split[1];

        return $type;
    }
}