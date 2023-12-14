<?php

declare(strict_types=1);
defined('TYPO3') or die();

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

    // Add wizard with map for setting geo location
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1546531781] = [
        'nodeName' => 'locationMapWizard',
        'priority' => 30,
        'class' => \WerkraumMedia\Maps\FormEngine\FieldControl\LocationMapWizard::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['allowCredentials'] = false;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['allowOrigin'] = ['http://localhost:1234'];
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['allowHeaders'] = ['*'];
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['allowMethods'] = ['*'];
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['exposeHeaders'] = [];
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['maxAge'] = 0;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3api']['cors']['originRegex'] = false;

     // Allow backend users to drag and drop the new page type:
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $poiDoktype . ')'
    );

})();