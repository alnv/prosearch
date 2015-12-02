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

if(TL_MODE == 'BE')
{
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/underscore-min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/zepto.min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/backbone-min.js|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/ProSearch.js|static';
}
/**
 * Example for Search Engine
 * SELECT * FROM tl_prosearch_data WHERE MATCH (title, search_content) AGAINST ('*ne*' IN BOOLEAN MODE) ORDER BY tstamp, clicks DESC;
 */