<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Pro Search
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2015 Alexander Naumov
 */

//use \Contao\BackendUser;

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['prosearch_settings'] = array(
    'tables' => array('tl_prosearch_settings', 'tl_prosearch_data')
);

/**
 * Widgets
 */
$GLOBALS['BE_FFL']['ajaxSearchIndex'] = 'AjaxSearchIndex';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ProSearch', 'createOnSubmitCallback');
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('PermissionHandler', 'insertProSearchLegend');
$GLOBALS['TL_HOOKS']['postLogin'][] = array('UserSettings', 'setUserSettingsOnLogin');
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('UserSettings', 'getUserSettings');

//
if(TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'system/modules/prosearch/assets/css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/underscore-min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/ProSearch.js|static';

}

