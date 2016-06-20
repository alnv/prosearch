<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   CC BY-NC-ND 4.0
 * @copyright 2016 Alexander Naumov
 */

$GLOBALS['PS_PUBLIC_PATH'] = 'system/modules/prosearch/assets/';
if ((version_compare(VERSION, '4.0', '>=') && !$GLOBALS['PS_NO_COMPOSER'] && $GLOBALS['PS_NO_COMPOSER'] != true)) {
    $GLOBALS['PS_PUBLIC_PATH'] = 'bundles/prosearch/';
}

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['prosearch_settings'] = array(
    'tables' => array('tl_prosearch_settings', 'tl_prosearch_data'),
    'icon' => $GLOBALS['PS_PUBLIC_PATH'] . 'icon.png',
);

/**
 * Widgets
 */
$GLOBALS['BE_FFL']['ajaxSearchIndex'] = 'AjaxSearchIndex';
$GLOBALS['BE_FFL']['tagTextField'] = 'TagTextField';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ProSearch', 'createOnSubmitCallback');
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ProSearchPalette', 'insertProSearchLegend');
$GLOBALS['TL_HOOKS']['postLogin'][] = array('UserSettings', 'setUserSettingsOnLogin');
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('UserSettings', 'getUserSettings');


// assets
if (TL_MODE == 'BE') {
    $GLOBALS['TL_CSS'][] = $GLOBALS['PS_PUBLIC_PATH'] . 'css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['PS_PUBLIC_PATH'] . 'vendor/underscore-min.js|static';
    $strProSearchHeader = '<span id="openProSearch" class="header_prosearch_container"><a href="#!prosearch" class="header_prosearch prosearch_backend_icon" title="ProSearch | Backend-Suche für Contao" >ProSearch</a></span>';
    if (version_compare(VERSION, '4.2', '>=')) {
        $strProSearchHeader = '<li id="openProSearch" class="header_prosearch_container"><a href="#!prosearch" class="header_prosearch" title="ProSearch | Backend-Suche für Contao" ><img src="' . $GLOBALS['PS_PUBLIC_PATH'] . 'prosearch.svg" width="18" height="18" alt="ProSearch | Backend-Suche für Contao" title=""></a></li>';
    }
    $GLOBALS['TL_MOOTOOLS'][] = "<script>var strProSearchHeaderTemplate = '$strProSearchHeader';</script>";
    $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['PS_PUBLIC_PATH'] . 'ProSearch.js|static';
}

// get editable files
$GLOBALS['PS_EDITABLE_FILES'] = explode(',', (\Contao\Config::get('editableFiles')));


$license = \Contao\Config::get('prosearchLicense');
if (!isset($license) || !in_array(md5($license), ProSearch\Helper::$validSums, true)) {

    if (TL_MODE == 'BE') {
        $GLOBALS['TL_MOOTOOLS'][] = '<script>var validLicense = false;</script>';
    }

}

