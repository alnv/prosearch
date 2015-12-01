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
use Contao\Controller;
use ProSearch\Helper;
use Contao\Config;

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

    /**
     * @param $searchDataDB
     * @param $arr
     * @return array
     */
    public function fillNewIndexWithExistData($searchDataDB, $arr)
    {

        if($searchDataDB->count() == 0)
        {
            return $arr;
        }

        while($searchDataDB->next())
        {
            for($i = 0; $i < count($arr); $i++)
            {
                // defaut
                if( $searchDataDB->docId == $arr[$i]['docId'] && $searchDataDB->doTable == $arr[$i]['doTable'] )
                {
                    $arr[$i]['id'] = $searchDataDB->id;
                    $arr[$i]['tags'] = $searchDataDB->tags;
                    $arr[$i]['clicks'] = $searchDataDB->clicks;
                }

            }
        }

        return $arr;
    }

    /**
     * @param $indexData
     */
    public function saveIndexDataIntoDB($data, $dca)
    {
        //reset table
        $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE dca = ?')->execute($dca);

        // insert new cols
        foreach($data as $arr)
        {

            // values
            $values = array_values($arr);
            $placeholder = implode(',', array_fill(0,count($values), '?'));

            // cols
            $cols = array_keys($arr);
            $cols = implode(',',$cols);

            // db operations
            $this->Database->prepare('INSERT INTO tl_prosearch_data('.$cols.') VALUES ('.$placeholder.')')->execute($values);

        }
    }

    /**
     * @param $db
     * @param $dca
     * @param $do
     * @return array|bool
     */
    public function prepareIndexData($db, $dca, $do)
    {
        // break up if data has no id
        if( !$db['id'] || !$do )
        {
            return false;
        }

        // set config
        $arr = array(
            'dca' => $do,
            'tstamp' => time(),
            'doTable' => substr($do, 3, strlen($do)),
            'ptable' => $dca['config']['ptable'] ? $dca['config']['ptable'] : '',
            'ctable' => $dca['config']['ctable'] ? serialize($dca['config']['ctable']) : '',
            'docId' => $db['id'],
            'pid' => $db['pid'] ? $db['pid'] : ''
        );

        // exception for tl_content
        if( $do == 'tl_content' && $arr['ptable'] == '')
        {
            $arr['ptable'] = $db['ptable'] ? $db['ptable'] : '';
        }

        // exception for tl_file
        if( $do == 'tl_files' )
        {
            $arr['path'] = $db['path'] ? $db['path'] : '';
            $arr['docId'] = '';
            $arr['pid'] = '';
        }

        // set operations
        $arr = $this->pluckOperations($dca, $arr);

        // set type
        $arr['type'] = $this->setType($db) ? $this->setType($db) : '';

        // set search content
        $arr['search_content'] = $this->setSearchContent($db) ? $this->setSearchContent($db) : '';

        // set title
        $arr['title'] = $this->setTitle($db) ? $this->setTitle($db) : 'no title';

        return $arr;
    }


    public function setType($db)
    {
        $colsForTypes = array('type');

        foreach($colsForTypes as $type)
        {
            if( $db[$type] && is_string($db[$type]) && $db[$type] != '' )
            {
                $meta = Helper::parseStrForMeta($db[$type]);
                $return = $meta != '' ? ' '.$meta : ' '.$db[$type];
                $return = Controller::replaceInsertTags($return);
                $return = strip_tags($return);
                $return = trim($return);
                return $return;
                break;

            }
        }

        return;

    }

    /**
     * @param $db
     * @return string|void
     */
    public function setSearchContent($db)
    {

        $title = $this->setTitle($db);

        $colsSearchContent = array('ps_description', 'text', 'teaser', 'description', 'content', 'meta');

        $strContent = $title;

        foreach($colsSearchContent as $content)
        {
            if( $db[$content] && is_string($db[$content]) && $db[$content] != '' )
            {
                $meta = Helper::parseStrForMeta($db[$content]);
                $strContent .= $meta != '' ? ' '.$meta : ' '.$db[$content];
            }
        }

        $strContent = Controller::replaceInsertTags($strContent);
        $strContent = strip_tags($strContent);
        $strContent = trim($strContent);

        return $strContent;

    }

    /**
     * @param $db
     */
    public function setTitle($db)
    {
        // sorted by priority
        $colsForTitle = array('ps_title', 'title', 'name', 'alias', 'username', 'headline');

        foreach($colsForTitle as $title)
        {
            if( $db[$title] && is_string($db[$title]) && $db[$title] != '' )
            {
                $meta = Helper::parseStrForMeta($db[$title]);

                $return = $meta != '' ? ' '.$meta : ' '.$db[$title];
                $return = Controller::replaceInsertTags($return);
                $return = strip_tags($return);
                $return = trim($return);

                return $return;
                break;
            }
        }

        return;
    }

    /**
     * @param $dca
     * @param $arr
     * @return array
     */
    public function pluckOperations($dca, $arr)
    {
        //
        $operations = $dca['list']['operations'];

        //
        $allowedOps = array(
            'cmdEdit' => '0',
            'cmdDelete' => '0',
            'cmdPaste' => '0',
            'cmdShow' => '0',
            'cmdAjaxPublished' => '0',
        );

        //
        if(is_array($operations) && !empty($operations))
        {
            // @todo cmdAjaxEdit // cmdCreate
            foreach($operations as $act => $operation)
            {

                // edit
                if( $act == 'edit')
                {
                    $allowedOps['cmdEdit'] = '1';
                }

                //delete
                if( $act == 'delete')
                {
                    $allowedOps['cmdDelete'] = '1';
                }

                //past
                if( $act == 'copy')
                {
                    $allowedOps['cmdPaste'] = '1';
                }

                //show
                if( $act == 'show')
                {
                    $allowedOps['cmdShow'] = '1';
                }

                //toggle
                if( $act == 'toggle')
                {
                    $allowedOps['cmdAjaxPublished'] = '1';
                }
            }
        }

        return array_merge($arr, $allowedOps);

    }

}