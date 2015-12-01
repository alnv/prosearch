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

// extend palettes
foreach( $GLOBALS['TL_DCA']['tl_content']['palettes'] as $palette => $str )
{
    if($palette == '__selector__')
    {
        continue;
    }

    if($palette == 'default')
    {
        continue;
    }

    $GLOBALS['TL_DCA']['tl_content']['palettes'][$palette] = str_replace('type', 'type,ps_title', $str);

}

// pro Search title
$GLOBALS['TL_DCA']['tl_content']['fields']['ps_title'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['ps_title'],
    'inputType' => 'text',
    'eval' => array('maxlength' => 255, 'tl_class' => 'long'),
    'sql' => "varchar(255) NOT NULL default ''"
);