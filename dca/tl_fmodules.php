<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2015 Alexander Naumov
 */
  
$GLOBALS['TL_DCA']['tl_fmodules']['palettes']['default'] = str_replace
(
    'tablename',
    'tablename,ps_shortcut',
    $GLOBALS['TL_DCA']['tl_fmodules']['palettes']['default']
);

  
$GLOBALS['TL_DCA']['tl_fmodules']['fields']['ps_shortcut'] = array(
	
	'label' => &$GLOBALS['TL_LANG']['tl_fmodules']['ps_shortcut'],
	'inputType' => 'text',
	'exclude' => true,
    'eval' => array('doNotCopy' => true, 'spaceToUnderscore' => true, 'maxlength' => 32, 'tl_class' => 'w50'),
	'sql' => "varchar(32) NOT NULL default ''"

);
