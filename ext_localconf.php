<?php

declare(strict_types=1);
defined('TYPO3_MODE') || die('Access denied.');

(function ($extKey='maps') {

    $poiDoktype = 116;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import "EXT:maps/Configuration/TSconfig/Page/setup.tsconfig"'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '@import "EXT:maps/Configuration/TypoScript/constants.typoscript"'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:maps/Configuration/TypoScript/setup.typoscript"'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );

    $iconRegistry->registerIcon(
        'maps-ce',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:maps/Resources/Public/Icons/Ce.svg'
        ]
    );

    $iconRegistry->registerIcon(
        'maps-page',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:maps/Resources/Public/Icons/Page.svg'
        ]
    );

     // Allow backend users to drag and drop the new page type:
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $poiDoktype . ')'
    );

})();