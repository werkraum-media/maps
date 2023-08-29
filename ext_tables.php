<?php

declare(strict_types=1);
defined('TYPO3') or die();

use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

(function () {

    $poiDoktype = 116;

    if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() > 11) {

        $dokTypeRegistry = GeneralUtility::makeInstance(PageDoktypeRegistry::class);
        $dokTypeRegistry->add($poiDoktype,  
        [
            'type' => 'web',
            'allowedTables' => '*',
        ]);

    } else {

        $GLOBALS['PAGES_TYPES'][$poiDoktype] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];

    }
 
})();
