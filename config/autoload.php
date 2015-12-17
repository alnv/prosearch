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

// namespace
\Contao\ClassLoader::addNamespace('ProSearch');

$path = 'system/modules/prosearch/';

// load classes
ClassLoader::addClasses(array(

    'ProSearch\ProSearch' => $path.'src/Resources/contao/classes/ProSearch.php',
    'ProSearch\Helper' => $path.'src/Resources/contao/classes/Helper.php',
    'ProSearch\CheckPermission' => $path.'src/Resources/contao/classes/CheckPermission.php',
    'ProSearch\UserSettings' => $path.'src/Resources/contao/classes/UserSettings.php',
    'ProSearch\ProSearchPalette' => $path.'src/Resources/contao/classes/ProSearchPalette.php',
    'ProSearch\PrepareDataException' => $path.'src/Resources/contao/classes/PrepareDataException.php',
    'ProSearch\ProSearchDataContainer' => $path.'src/Resources/contao/classes/ProSearchDataContainer.php',
    'ProSearch\AjaxSearchIndex' => $path.'src/Resources/contao/widgets/AjaxSearchIndex.php',
    'ProSearch\TagTextField' => $path.'src/Resources/contao/widgets/TagTextField.php'

));
