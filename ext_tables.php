<?php

declare(strict_types=1);
defined('TYPO3_MODE') || die('Access denied.');

(function ($extKey='maps') {

    $poiDoktype = 116;
 
    $GLOBALS['PAGES_TYPES'][$poiDoktype] = [
        'type' => 'web',
        'allowedTables' => '*',
    ];
 
})();
