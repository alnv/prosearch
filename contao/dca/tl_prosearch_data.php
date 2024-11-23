<?php

$GLOBALS['TL_DCA']['tl_prosearch_data'] = array(

    // config
    'config' => array
    (
        'dataContainer' => 'Table',

        'sql' => array(
            'keys' => array(
                'id' => 'primary',
                'tags' => 'fulltext',
                'search_content' => 'fulltext'
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
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'search_content' => array
        (
            'sql' => "text NULL"
        ),

        'clicks' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),

        'icon' => array
        (
            'sql' => "varchar(512) NOT NULL default ''"
        ),

        'tags' => array(
            'sql' => "varchar(1024) NOT NULL default ''"
        ),

        'blocked' => array(
            'sql' => "char(1) NOT NULL default ''"
        ),

        'blocked_ug' => array(
            'sql' => "blob NULL",
        ),

        'extension' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        ),

        'shortcut' => array
        (
            'sql' => "varchar(32) NOT NULL default ''"
        ),

        'type' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        )

    )
);