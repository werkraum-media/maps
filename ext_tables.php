<?php

declare(strict_types=1);
defined('TYPO3') || die('Access denied from extension maps in ext_tables.');

(function ($extKey='maps') {

    $poiDoktype = 116;
 
    $GLOBALS['PAGES_TYPES'][$poiDoktype] = [
        'type' => 'web',
        'allowedTables' => '*',
    ];
 
})();
