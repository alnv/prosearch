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

use ProSearch\ProSearch;

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
            array('tl_prosearch_settings', 'ajaxSearchIndex'),
            array('ProSearch', 'ajaxRequest')
        ),
        'onsubmit_callback' => array(
            array('ProSearch', 'deleteModulesFromIndex')
        ),
    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{settings_legend},searchIndexModules,createIndex;'
    ),

    // Fields
    'fields' => array
    (
        'searchIndexModules' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_settings']['searchIndexModules'],
            'inputType' => 'checkbox',
            'options_callback' => array('ProSearch', 'loadModules'),
            'eval' => array('multiple' => true),
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
class tl_prosearch_settings extends ProSearch
{
    /**
     * ajax call
     */
    public function ajaxSearchIndex()
    {
        // ajax
        if (strlen(Input::get('index'))) {

            $toIndex = Input::get('index');

            // load dca
            $this->loadDataContainer($toIndex);

            // set not found if module not exist
            if(!$GLOBALS['TL_DCA'][$toIndex])
            {
                echo '<li class="failure">'.$toIndex.' <span>[x]</span></li>';
                exit;
            }

            $dcaDB = $this->Database->prepare('SELECT * FROM '.$toIndex.'')->execute();

            // set empty if module do not have any data
            if($dcaDB->count() < 1)
            {
                echo '<li class="empty">'.$toIndex.' <span>[-]</span></li>';
                exit;
            }

            // loop through data and create index
            $arr = array();

            while($dcaDB->next())
            {
                $data = $this->prepareIndexData($dcaDB->row(), $GLOBALS['TL_DCA'][$toIndex], $toIndex);

                if($data == false)
                {
                    continue;
                }

                $arr[] = $data;

            }

            // save in db
            if(count($arr) > 0)
            {

                $searchDataDB = $this->Database->prepare('SELECT * FROM tl_prosearch_data WHERE dca = ?')->execute($toIndex);

                // check if data already exist in search index
                // get all items, which should me delete
                $newIndexData = $this->fillNewIndexWithExistData($searchDataDB, $arr);

                // save
                $this->saveIndexDataIntoDB($newIndexData, $toIndex);

            }

            echo '<li class="success">'.$toIndex.' <span>[√]</span></li>';
            exit;
        }
    }
}