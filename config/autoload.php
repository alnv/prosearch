<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   Pro Search
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   CC BY-NC-ND 4.0
 * @copyright 2016 Alexander Naumov
 */

// namespace
ClassLoader::addNamespace('ProSearch');

$proSearchPath = 'system/modules/prosearch/';

if( (version_compare(VERSION, '4.0', '>=') && !$GLOBALS['FM_NO_COMPOSER'] && $GLOBALS['FM_NO_COMPOSER'] != true ) )
{
    $proSearchPath = 'vendor/prosearch/prosearch/';
}

// load classes
ClassLoader::addClasses(array(
    'ProSearch\ProSearch' => $proSearchPath.'src/Resources/contao/classes/ProSearch.php',
    'ProSearch\Helper' => $proSearchPath.'src/Resources/contao/classes/Helper.php',
    'ProSearch\UserSettings' => $proSearchPath.'src/Resources/contao/classes/UserSettings.php',
    'ProSearch\ProSearchPalette' => $proSearchPath.'src/Resources/contao/classes/ProSearchPalette.php',
    'ProSearch\PrepareDataException' => $proSearchPath.'src/Resources/contao/classes/PrepareDataException.php',
    'ProSearch\ProSearchDataContainer' => $proSearchPath.'src/Resources/contao/classes/ProSearchDataContainer.php',
    'ProSearch\AjaxSearchIndex' => $proSearchPath.'src/Resources/contao/widgets/AjaxSearchIndex.php',
    'ProSearch\TagTextField' => $proSearchPath.'src/Resources/contao/widgets/TagTextField.php'
));
