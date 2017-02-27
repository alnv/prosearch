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

ClassLoader::addNamespace('ProSearch');

$strProSearchPath = 'system/modules/prosearch/';

if( (version_compare(VERSION, '4.0', '>=') && !$GLOBALS['FM_NO_COMPOSER'] && $GLOBALS['FM_NO_COMPOSER'] != true ) ) {

    $strProSearchPath = 'vendor/prosearch/prosearch/';
}

ClassLoader::addClasses([

    'ProSearch\ProSearch' => $strProSearchPath . 'src/Resources/contao/classes/ProSearch.php',
    'ProSearch\Helper' => $strProSearchPath . 'src/Resources/contao/classes/Helper.php',
    'ProSearch\UserSettings' => $strProSearchPath . 'src/Resources/contao/classes/UserSettings.php',
    'ProSearch\ProSearchPalette' => $strProSearchPath . 'src/Resources/contao/classes/ProSearchPalette.php',
    'ProSearch\PrepareDataException' => $strProSearchPath . 'src/Resources/contao/classes/PrepareDataException.php',
    'ProSearch\ProSearchDataContainer' => $strProSearchPath . 'src/Resources/contao/classes/ProSearchDataContainer.php',
    'ProSearch\AjaxSearchIndex' => $strProSearchPath . 'src/Resources/contao/widgets/AjaxSearchIndex.php',
    'ProSearch\TagTextField' => $strProSearchPath . 'src/Resources/contao/widgets/TagTextField.php',
    'ProSearch\InitializeProSearch' => $strProSearchPath . 'src/Resources/contao/classes/InitializeProSearch.php'
]);
