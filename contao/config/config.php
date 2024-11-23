<?php

use Contao\Config;
use Alnv\ProSearchBundle\Classes\ProSearch;
use Alnv\ProSearchBundle\Classes\UserSettings;
use Alnv\ProSearchBundle\Classes\ProSearchPalette;
use Alnv\ProSearchBundle\Classes\InitializeProSearch;

$GLOBALS['BE_MOD']['system']['prosearch_settings'] = [
    'tables' => [
        'tl_prosearch_settings',
        'tl_prosearch_data'
    ]
];

$GLOBALS['BE_FFL']['ajaxSearchIndex'] = 'AjaxSearchIndex';
$GLOBALS['BE_FFL']['tagTextField'] = 'TagTextField';

$GLOBALS['TL_HOOKS']['loadDataContainer'][] = [ProSearch::class, 'createOnSubmitCallback'];
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = [UserSettings::class, 'initializeSettings'];
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = [ProSearchPalette::class, 'insertProSearchLegend'];
$GLOBALS['TL_HOOKS']['initializeSystem'][] = [InitializeProSearch::class, 'setScriptPlaceholder'];

if (TL_MODE == 'BE') {

    $GLOBALS['TL_CSS'][] = 'bundles/alnvprosearch/css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/alnvprosearch/vendor/underscore-min.js|static';

    $strButton = '<li id="%s" class="%s"><a href="%s" class="%s" title="%s">%s</a></li>';

    $strText = 'ProSearch';
    $strId = 'openProSearch';
    $strHref = '#!prosearch';
    $strIcon = 'prosearch_v4icon';
    $strItemCssClass = 'header_prosearch';
    $strContainerCssClass = 'header_prosearch_container';
    $strTitle = ($GLOBALS['TL_LANG']['MSC']['prosearchTitle'] ?? '');

    $strButton = sprintf($strButton,
        $strId,
        $strContainerCssClass,
        $strHref,
        $strItemCssClass . ' ' . $strIcon,
        $strTitle,
        $strText
    );

    $GLOBALS['TL_MOOTOOLS'][] = "<script>var strProSearchHeaderTemplate = '$strButton';</script>";
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/alnvprosearch/ProSearch.js|static';
}

$GLOBALS['PS_EDITABLE_FILES'] = explode(',', (Config::get('editableFiles')));

