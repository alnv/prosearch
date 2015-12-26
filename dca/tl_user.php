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

// Palette erweitern
$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['login']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['admin']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['default'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['group'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['group']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('backendTheme;', 'backendTheme;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);

/**
 *
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['keyboard_shortcut'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['keyboard_shortcut'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'w50', 'helpwizard' => true),
    'explanation' => 'keyboard_shortcut',
    'sql' => "varchar(12) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['config']['onsubmit_callback'][] = array('UserSettings', 'setUserSettingsOnSave');
