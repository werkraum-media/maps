<?php

declare(strict_types=1);
defined('TYPO3_MODE') or die();

(function ($extKey='maps', $table='pages') {

        $poiDoktype = 116;

        // Add new page type as possible select item:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            $table,
            'doktype',
            [
                'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.title',
                $poiDoktype,
                'EXT:' . $extKey . '/Resources/Public/Icons/Page.svg'
            ],
            '1',
            'after'
        );

        // Add icon for new page type:
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA'][$table],
            [
                'ctrl' => [
                    'typeicon_classes' => [
                        $poiDoktype => 'maps-page',
                    ],
                ],
                'types' => [
                    (string) $poiDoktype => $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT],
                ],
            ]
        );

        // Add TCA fields
        $temporaryColumns = [
            'geo_lat' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.lat',
                'config' => [
                    'type' => 'input',
                    'size' => '12',
                    'eval' => 'float',
                    'default' => '',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_long' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.lng',
                'config' => [
                    'type' => 'input',
                    'size' => '12',
                    'eval' => 'float',
                    'default' => '',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                    'fieldControl' => [
                        'locationMap' => [
                            'renderType' => 'locationMapWizard'
                        ]
                    ],
                ],
            ],
            'geo_title' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.title',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_subtitle' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.subtitle',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_address' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.address',
                'config' => [
                    'type' => 'text',
                    'cols' => '24',
                    'rows' => '6',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_phone' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.phone',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_email' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.email',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_www' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.www',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_booking' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.booking',
                'config' => [
                    'type' => 'input',
                    'size' => '24',
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'geo_type' => [
                'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.type',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['None', -1],
                        ['LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.city', 'city'],
                        ['LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.sight', 'sight'],
                        ['LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.host', 'host'],
                        ['LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.bar', 'bar'],
                        ['LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang.xlf:page.poi.shop', 'shop'],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'pages',
            $temporaryColumns
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'pages',
            '--div--;Geodata, geo_lat, geo_long, geo_title, geo_subtitle, geo_address, geo_phone, geo_email, geo_www, geo_booking, geo_type',
            $poiDoktype
        );

})();
