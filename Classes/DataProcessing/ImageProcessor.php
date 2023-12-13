<?php

namespace WerkraumMedia\Maps\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Extbase\Service\ImageService;

class ImageProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $targetWidth = $processorConfiguration['targetWidth'] ?? 200;
        $targetHeight = $processorConfiguration['targetHeight'] ?? 200;

        $files = $processedData['files'] ?? [];

        foreach ($files as $key => $file) {
            if ($file instanceof FileInterface) {
                // Bearbeiten des Bildes
                $resizedImageUrl = $this->resizeImage($file, $targetWidth, $targetHeight);
                // Ersetzen des ursprünglichen Bild-URLs im processedData-Array
                $processedData['files'][$key] = $resizedImageUrl;
            }
        }

        return $processedData;
    }
    
    protected function resizeImage(FileInterface $file, $targetWidth, $targetHeight)
    {
        $imageService = GeneralUtility::makeInstance(ImageService::class);

        $processingConfiguration = [
            'width' => $targetWidth,
            'height' => $targetHeight,
            'minWidth' => 10,
            'minHeight' => 10,
        ];
    
        $processedImage = $imageService->applyProcessingInstructions($file, $processingConfiguration);
        print_r($processedImage);
        print_r($imageService->getImageUri($processedImage, true));
        if ($processedImage instanceof ProcessedFile && $processedImage->exists()) {
            print_r('Yes');
            return $processedImage->getPublicUrl();
        } else {
            throw new \RuntimeException('Die Bildbearbeitung ist fehlgeschlagen.');
        }
    }
}