<?php

use Alnv\ProSearchBundle\Classes\ProSearch;
use Contao\Controller;
use Contao\Database;
use Contao\DC_File;
use Contao\Input;

$GLOBALS['TL_DCA']['tl_prosearch_settings'] = array(
    'config' => array
    (
        'dataContainer' => DC_File::class,
        'closed' => true,
        'onload_callback' => array
        (
            array(tl_prosearch_settings::class, 'ajaxSearchIndex'),
            array(ProSearch::class, 'ajaxRequest')
        ),
        'onsubmit_callback' => array(
            array(ProSearch::class, 'deleteModulesFromIndex')
        ),
    ),
    'palettes' => array
    (
        'default' => '{settings_legend},searchIndexModules,addDescriptionToSearchContent,createIndex'
    ),
    'fields' => array
    (
        'searchIndexModules' => array
        (
            'inputType' => 'checkbox',
            'options_callback' => array(ProSearch::class, 'loadModules'),
            'eval' => array('multiple' => true),
            'sql' => "blob NULL"
        ),
        'addDescriptionToSearchContent' => array(
            'inputType' => 'checkbox',
        ),
        'createIndex' => array(
            'inputType' => 'ajaxSearchIndex'
        )
    )
);

class tl_prosearch_settings
{

    public function ajaxSearchIndex(): void
    {

        if (Input::get('index')) {

            $tableToIndex = Input::get('index');
            $pageNum = Input::get('page') ? (int)Input::get('page') : 0;
            $limit = 1000;

            Controller::loadDataContainer($tableToIndex);

            if (!$GLOBALS['TL_DCA'][$tableToIndex]) {
                $data = array('state' => 'failure', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                header('Content-type: application/json');
                echo json_encode($data);
                exit;
            }

            $dataDB = Database::getInstance()->prepare('SELECT * FROM ' . $tableToIndex)->execute();
            $count = $dataDB->count();

            if ($count < 1) {
                $data = array('state' => 'empty', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                header('Content-type: application/json');
                echo json_encode($data);
                exit;
            }

            if ($count >= $limit) {

                $skip = $count - ($pageNum * $limit);
                $offset = $limit * $pageNum;

                if ($skip > 0) {
                    $dataDBLong = Database::getInstance()->prepare('SELECT * FROM ' . $tableToIndex . ' LIMIT ' . $offset . ',' . $limit)->execute();
                    $this->saveToIndex($dataDBLong, $tableToIndex, $pageNum);
                    $data = array('state' => 'repeat', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => $skip);
                    header('Content-type: application/json');
                    echo json_encode($data);
                    exit;

                } else {

                    $data = array('state' => 'success', 'table' => $tableToIndex, 'page' => $pageNum, 'left' => 0);
                    header('Content-type: application/json');
                    echo json_encode($data);
                    exit;

                }
            }

            if ($count < $limit) {
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
        $objProSearch = new ProSearch();

        while ($dataDB->next()) {
            $data = $objProSearch->prepareIndexData($dataDB->row(), ($GLOBALS['TL_DCA'][$tablename] ?? []), $tablename);

            if ($data == false) {
                continue;
            }

            $arr[] = $data;

        }
        $objProSearch->saveIndexDataIntoDB($arr, $tablename, $pageNum);
    }
}