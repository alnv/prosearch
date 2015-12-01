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

$GLOBALS['TL_DCA']['tl_prosearch_data'] = array(

    // config
    'config' => array
    (
        'dataContainer' => 'Table',

        'sql' => array(
            'keys' => array(
                'id' => 'primary'
            )
        )
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),

        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),

        'title' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'dca' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'doTable' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'ctable' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'ptable' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'pid' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),

        'docId' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),

        'cmdEdit' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdDelete' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdPaste' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdShow' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdCreate' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdAjaxEdit' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'cmdAjaxPublished' => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),

        'search_content' => array
        (
            'sql' => "text NULL"
        ),

        'clicks' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),

        'tags' => array
        (
            'sql' => "blob NULL"
        ),

        'shortcut' => array
        (
            'sql' => "varchar(32) NOT NULL default ''"
        ),

        'type' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'path' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        )

    )
);