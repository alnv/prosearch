<?php

$GLOBALS['TL_DCA']['tl_prosearch_tags'] = array(
    'config' => array
    (
        'dataContainer' => 'Table',
        'sql' => array(
            'keys' => array(
                'id' => 'primary'
            )
        )
    ),
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
        'tagname' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        )
    )
);