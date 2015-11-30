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
    'tables' => array('tl_prosearch_settings')
);

/**
 * Widgets
 */
$GLOBALS['BE_FFL']['ajaxSearchIndex'] = 'AjaxSearchIndex';

/**
 * hooks
 */
