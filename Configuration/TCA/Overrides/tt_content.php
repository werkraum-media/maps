<?php

declare(strict_types=1);
defined('TYPO3') or die();

(function ($extKey='maps', $table='tt_content') {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItemGroup(
        $table,
        'CType',
        $extKey,
        'LLL:EXT:maps/Resources/Private/Language/locallang.xlf:group.title',
        'after:special'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        $table,
        'CType',
        [
            'LLL:EXT:maps/Resources/Private/Language/locallang.xlf:ce.title',
            'map',
            'maps-ce',
        ],
        'textmedia',
        'after'
    );

    /*
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:maps/Configuration/FlexForms/Flexform.xml',
        'map'
    );
    */

    /*
            'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                header; Internal title,
                pages; Pages,
                pi_flexform,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
        ',
    */
    

    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['map'] = 'maps-ce';

    $GLOBALS['TCA']['tt_content']['types']['map'] = [
        'ctrl' => [
            'iconfile' => 'EXT:maps/Resources/Public/Icons/Ce.svg',
         ],
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                header; Internal title,
                pages; Pages,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
        ',
    ];

})();
