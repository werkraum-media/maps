<?php
declare(strict_types = 1);
namespace WerkraumMedia\Maps\FormEngine\FieldControl;

/**
 * This file is part of the "maps" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Adds a wizard for location selection via map
 */
class LocationMapWizard extends AbstractNode
{
    /**
     * @return array
     */
    public function render(): array
    {
        $row = $this->data['databaseRow'];
        $paramArray = $this->data['parameterArray'];
        $resultArray = $this->initializeResultArray();

        $nameLongitude = $paramArray['itemFormElName'];
        $nameLatitude = str_replace('geo_long', 'geo_lat', $nameLongitude);
        $nameLatitudeActive = str_replace('data', 'control[active]', $nameLatitude);
        $geoCodeUrl = $geoCodeUrlShort = '';
        $gLat = '55.6760968';
        $gLon = '12.5683371';

        $lat = $row['geo_lat'] != '' ? htmlspecialchars($row['geo_lat']) : '';
        $lon = $row['geo_long'] != '' ? htmlspecialchars($row['geo_long']) : '';

        if ($row['geo_lat'] || $row['geo_long'] == '') {
            // remove all after first slash in address (top, floor ...)
            $address = preg_replace('/^([^\/]*).*$/', '$1', $row['geo_address'] ?? '') . ' ';
            $address .= $row['city'] ?? '';
            // if we have at least some address part (saves geocoding calls)
            if ($address) {
                // base url
                $geoCodeUrlBase = 'https://nominatim.openstreetmap.org/search/';
                $geoCodeUrlAddress = $address;
                $geoCodeUrlCityOnly = ($row['city'] ?? '');
                // urlparams for nominatim which are fixed.
                $geoCodeUrlQuery = '?format=json&addressdetails=1&limit=1&polygon_svg=1';
                // replace newlines with spaces; remove multiple spaces
                $geoCodeUrl = trim(preg_replace('/\s\s+/', ' ', $geoCodeUrlBase . $geoCodeUrlAddress . $geoCodeUrlQuery));
                $geoCodeUrlShort = trim(preg_replace('/\s\s+/', ' ', $geoCodeUrlBase . $geoCodeUrlCityOnly . $geoCodeUrlQuery));
            }
        }

        $resultArray['iconIdentifier'] = 'location-map-wizard';
        $resultArray['title'] = $this->getLanguageService()->sL('LLL:EXT:maps/Resources/Private/Language/locallang_db.xlf:locationMapWizard');
        $resultArray['linkAttributes']['class'] = 'locationMapWizard ';
        $resultArray['linkAttributes']['data-label-title'] = $this->getLanguageService()->sL('LLL:EXT:maps/Resources/Private/Language/locallang_db.xlf:locationMapWizard');
        $resultArray['linkAttributes']['data-label-close'] = $this->getLanguageService()->sL('LLL:EXT:maps/Resources/Private/Language/locallang_db.xlf:locationMapWizard.close');
        $resultArray['linkAttributes']['data-label-import'] = $this->getLanguageService()->sL('LLL:EXT:maps/Resources/Private/Language/locallang_db.xlf:locationMapWizard.import');
        $resultArray['linkAttributes']['data-lat'] = $lat;
        $resultArray['linkAttributes']['data-lon'] = $lon;
        $resultArray['linkAttributes']['data-glat'] = $gLat;
        $resultArray['linkAttributes']['data-glon'] = $gLon;
        $resultArray['linkAttributes']['data-geocodeurl'] = $geoCodeUrl;
        $resultArray['linkAttributes']['data-geocodeurlshort'] = $geoCodeUrlShort;
        $resultArray['linkAttributes']['data-namelat'] = htmlspecialchars($nameLatitude);
        $resultArray['linkAttributes']['data-namelon'] = htmlspecialchars($nameLongitude);
        $resultArray['linkAttributes']['data-namelat-active'] = htmlspecialchars($nameLatitudeActive);
        $resultArray['linkAttributes']['data-tiles'] = htmlspecialchars('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        $resultArray['linkAttributes']['data-copy'] = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        $resultArray['stylesheetFiles'][] = 'EXT:maps/Resources/Public/Stylesheets/leaflet/leaflet-1.9.4.css';
        $resultArray['stylesheetFiles'][] = 'EXT:maps/Resources/Public/Stylesheets/backend/locationMapWizard.css';

        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion();
        if ($versionInformation > 11) {
            $id = StringUtility::getUniqueId('t3js-formengine-fieldcontrol-');
            $resultArray['requireJsModules'][] = JavaScriptModuleInstruction::forRequireJS(
                'TYPO3/CMS/Maps/leaflet/leaflet-1.9.4'
            )->instance($id);
            $resultArray['requireJsModules'][] = JavaScriptModuleInstruction::forRequireJS(
                'TYPO3/CMS/Maps/backend/locationMapWizard'
            )->instance($id);
        } else {
            $resultArray['requireJsModules'][] = 'TYPO3/CMS/Maps/leaflet/leaflet-1.9.4';
            $resultArray['requireJsModules'][] = 'TYPO3/CMS/Maps/backend/locationMapWizard';
        }

        return $resultArray;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}