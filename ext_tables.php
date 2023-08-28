<?php

declare(strict_types=1);
defined('TYPO3') or die();

use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

(function ($extKey='maps') {

    $poiDoktype = 116;

    $dokTypeRegistry = GeneralUtility::makeInstance(PageDoktypeRegistry::class);
    $dokTypeRegistry->add($poiDoktype,  
    [
        'type' => 'web',
        'allowedTables' => '*',
    ]);
 
})();
