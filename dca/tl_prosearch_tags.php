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

$GLOBALS['TL_DCA']['tl_prosearch_tags'] = array(

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

        'tagname' => array
        (
            'sql' => "varchar(255) NOT NULL default ''"
        )
    )
);