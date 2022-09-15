<?php

$GLOBALS['BE_MOD']['system']['prosearch_settings'] = [

    'icon' => 'system/modules/prosearch/assets/icon.png',
    'tables' => [ 'tl_prosearch_settings', 'tl_prosearch_data' ]
];

$GLOBALS['BE_FFL']['ajaxSearchIndex'] = 'AjaxSearchIndex';
$GLOBALS['BE_FFL']['tagTextField'] = 'TagTextField';

$GLOBALS['TL_HOOKS']['loadDataContainer'][] = [ 'ProSearch', 'createOnSubmitCallback' ];
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = [ 'UserSettings', 'initializeSettings' ];
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = [ 'ProSearchPalette', 'insertProSearchLegend' ];
$GLOBALS['TL_HOOKS']['initializeSystem'][] = [ 'InitializeProSearch', 'setScriptPlaceholder' ];

if (TL_MODE == 'BE') {
    
    $GLOBALS['TL_CSS'][] = 'system/modules/prosearch/assets/css/theme.css|static';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/underscore-min.js|static';

    $strButton = '<li id="%s" class="%s"><a href="%s" class="%s" title="%s">%s</a></li>';

    $strText = 'ProSearch';
    $strId = 'openProSearch';
    $strHref = '#!prosearch';
    $strIcon = 'prosearch_v3icon';
    $strItemCssClass = 'header_prosearch';
    $strContainerCssClass =  'header_prosearch_container';
    $strTitle = $GLOBALS['TL_LANG']['MSC']['prosearchTitle'];

    if ( version_compare( VERSION, '4.2', '>=' ) )
    {
        $strIcon = 'prosearch_v4icon';
    }
    
    $strButton = sprintf( $strButton,

        $strId,
        $strContainerCssClass,
        $strHref,
        $strItemCssClass . ' ' . $strIcon,
        $strTitle,
        $strText
    );
    
    $GLOBALS['TL_MOOTOOLS'][] = "<script>var strProSearchHeaderTemplate = '$strButton';</script>";
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/ProSearch.js|static';
}

$GLOBALS['PS_EDITABLE_FILES'] = explode( ',', ( \Config::get('editableFiles') ) );

