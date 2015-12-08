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
        if (Input::get('index')) {

            // get table
            $tableToIndex = Input::get('index');
            $pageNum = Input::get('page') ? (int)Input::get('page') : 0;
            $limit = 1000;

            // load dca
            $this->loadDataContainer($tableToIndex);

            // ckeck if dca exist
            if(!$GLOBALS['TL_DCA'][$tableToIndex])
            {
                $data = array('state' => 'failure', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                header('Content-type: application/json');
                echo json_encode($data);
                exit;
            }

            $dataDB = $this->Database->prepare('SELECT * FROM '.$tableToIndex.'')->execute();
            $count = $dataDB->count();

            if($count < 1)
            {
                $data = array('state' => 'empty', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                header('Content-type: application/json');
                echo json_encode($data);
                exit;
            }

            if( $count >= $limit )
            {

                $skip = $count - ( $pageNum * $limit );
                $offset = $limit * $pageNum;

                if($skip > 0)
                {
                    $dataDBLong = $this->Database->prepare('SELECT * FROM '.$tableToIndex.' LIMIT '.$offset.','.$limit.'')->execute();
                    $this->saveToIndex($dataDBLong, $tableToIndex, $pageNum);
                    $data = array('state' => 'repeat', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => $skip);
                    header('Content-type: application/json');
                    echo json_encode($data);
                    exit;

                }else{

                    $data = array('state' => 'success', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                    header('Content-type: application/json');
                    echo json_encode($data);
                    exit;

                }


            }

            if( $count < $limit )
            {
                $this->saveToIndex($dataDB, $tableToIndex, 0);
                header('Content-type: application/json');
                $data = array('state' => 'success', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                echo json_encode($data);
                exit;
            }
        }
    }

    public function saveToIndex($dataDB, $tablename, $pageNum)
    {
        $arr = array();

        while($dataDB->next())
        {
            $data = $this->prepareIndexData($dataDB->row(), $GLOBALS['TL_DCA'][$tablename], $tablename);

            if($data == false)
            {
                continue;
            }

            $arr[] = $data;

        }

        $this->saveIndexDataIntoDB($arr, $tablename, $pageNum);

    }

}