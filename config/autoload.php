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
    'ProSearch\AjaxSearchIndex' => $path.'src/Resources/contao/widgets/AjaxSearchIndex.php'

));
