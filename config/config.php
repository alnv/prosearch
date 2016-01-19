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

$GLOBALS['PS_PUBLIC_PATH'] = 'system/modules/prosearch/assets/';
if( (version_compare(VERSION, '4.0', '>=') && !$GLOBALS['PS_NO_COMPOSER'] && $GLOBALS['PS_NO_COMPOSER'] != true ) )
{
    $GLOBALS['PS_PUBLIC_PATH'] = 'bundles/prosearch/';
}

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['prosearch_settings'] = array(
    'tables' => array('tl_prosearch_settings', 'tl_prosearch_data'),
    'icon' => $GLOBALS['PS_PUBLIC_PATH'].'icon.png',
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
    $GLOBALS['TL_CSS'][] = $GLOBALS['PS_PUBLIC_PATH'].'css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['PS_PUBLIC_PATH'].'vendor/underscore-min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['PS_PUBLIC_PATH'].'ProSearch.js|static';
}

// get editable files
$GLOBALS['PS_EDITABLE_FILES'] = explode(',', (\Contao\Config::get('editableFiles')));

/*
$license = \Contao\Config::get('prosearchLicense');

if ( !isset($license) || !in_array(md5($license), ProSearch\Helper::$validSums, true) ) {

		if(TL_MODE == 'FE')
		{
			$GLOBALS['TL_HEAD'][] = '<meta name="ProSearch.title" content="ProSearch | Eine Backend-Suche für Contao CMS" />';
			$GLOBALS['TL_HEAD'][] = '<meta name="ProSearch.developer" content="Alexander Naumov" />';
			$GLOBALS['TL_HEAD'][] = '<meta name="ProSearch.website" content="http://backend-suche-contao.alexandernaumov.de" />';
		}

}
*/
