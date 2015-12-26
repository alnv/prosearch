<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2015 Alexander Naumov
 */

$path = 'system/modules/prosearch/assets/';
if( (version_compare(VERSION, '4.0', '>=') && !$GLOBALS['PS_NO_COMPOSER'] && $GLOBALS['PS_NO_COMPOSER'] != true ) )
{
    $path = 'bundles/prosearch/';
}

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['prosearch_settings'] = array(
    'tables' => array('tl_prosearch_settings', 'tl_prosearch_data'),
    'icon' => $path.'icon.png',
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
    $GLOBALS['TL_CSS'][] = $path.'css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = $path.'vendor/underscore-min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = $path.'ProSearch.js|static';
}

// get editable files
$GLOBALS['PS_EDITABLE_FILES'] = explode(',', (\Contao\Config::get('editableFiles')));

$ip = Environment::get('ip');

if ( TL_MODE == 'FE' && $ip != '127.0.0.1' ) {

    $license = \Contao\Config::get('prosearchLicense');
    
    if ( !isset($license) || !in_array(md5($license), ProSearch\Helper::$validSums, true) ) {

        $GLOBALS['TL_HEAD'][] = '<meta name="ProSearch" content="#" />';
        $GLOBALS['TL_HEAD'][] = '<div><a href="#" style="background: tomato; font-size: 12px; display: block; position: fixed; bottom:0; width: 100%; height: 20px; line-height: 20px; text-align: center; z-index: 9999;color: #fff;">ProSearch Lizenz kaufen</a></div>';

    }
}
