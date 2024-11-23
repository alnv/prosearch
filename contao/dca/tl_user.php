<?php

$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['login']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['admin']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['default'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['group'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['group']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = \str_replace('password;', 'password;{prosearch_user_legend:hide},keyboard_shortcut;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);

$GLOBALS['TL_DCA']['tl_user']['fields']['keyboard_shortcut'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_user']['keyboard_shortcut'],
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'helpwizard' => true
    ],
    'explanation' => 'keyboard_shortcut',
    'exclude' => true,
    'sql' => "varchar(12) NOT NULL default ''"
];