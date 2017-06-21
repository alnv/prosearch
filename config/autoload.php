<?php

ClassLoader::addNamespace('ProSearch');

ClassLoader::addClasses([

    'ProSearch\Helper' => 'system/modules/prosearch/src/Resources/contao/classes/Helper.php',
    'ProSearch\ProSearch' => 'system/modules/prosearch/src/Resources/contao/classes/ProSearch.php',
    'ProSearch\TagTextField' => 'system/modules/prosearch/src/Resources/contao/widgets/TagTextField.php',
    'ProSearch\UserSettings' => 'system/modules/prosearch/src/Resources/contao/classes/UserSettings.php',
    'ProSearch\AjaxSearchIndex' => 'system/modules/prosearch/src/Resources/contao/widgets/AjaxSearchIndex.php',
    'ProSearch\ProSearchPalette' => 'system/modules/prosearch/src/Resources/contao/classes/ProSearchPalette.php',
    'ProSearch\InitializeProSearch' => 'system/modules/prosearch/src/Resources/contao/classes/InitializeProSearch.php',
    'ProSearch\PrepareDataException' => 'system/modules/prosearch/src/Resources/contao/classes/PrepareDataException.php',
    'ProSearch\ProSearchDataContainer' => 'system/modules/prosearch/src/Resources/contao/classes/ProSearchDataContainer.php'
]);
