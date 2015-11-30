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

use Contao\Backend;

/**
 * Pro Search configuration
 */
$GLOBALS['TL_DCA']['tl_prosearch_settings'] = array(

    // config
    'config' => array
    (
        'dataContainer' => 'File',
        'onload_callback' => array
        (
            array('tl_prosearch_settings', 'ajaxSearchIndex')
        ),
    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{settings_legend},modules,createIndex;'
    ),

    // Fields
    'fields' => array
    (
        'modules' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_settings']['modules'],
            'inputType' => 'checkbox',
            'options_callback' => array('ProSearch', 'loadModules'),
            'eval' => array('multiple' => true, 'mandatory' => true),
            'sql' => "blob NULL"
        ),

        'createIndex' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_settings']['createIndex'],
            'inputType' => 'ajaxSearchIndex'
        )
    )
);

/**
 * Class tl_prosearch_settings
 */
class tl_prosearch_settings extends Backend
{
    /**
     *
     */
    public function ajaxSearchIndex()
    {
        // ajax
        if (strlen(Input::get('index'))) {

            $toIndex = Input::get('index');

            //$this->Database->prepare('SELECT * FROM '.$toIndex.'')->execute();

            echo '<li class="success">'.$toIndex.' <span>√</span></li>';
            exit;
        }
    }


}