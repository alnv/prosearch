<?php namespace ProSearch;

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

use Contao\Config;
use Contao\Image;
use Contao\Input;
use Contao\Controller;


/**
 * Class ProSearch
 */
class ProSearch extends ProSearchDataContainer
{

    public $coreModules = array();
    public $deletedIndexData = array();
    public $modules = array(

        'article' => array(
            'shortcut' => 'ar',
            'icon' => 'article.gif',
            'tables' => array('tl_article'),
            'searchIn' => array('title'),
            'title' => array('title'),
        ),

        'page' => array(
            'shortcut' => 'pa',
            'tables' => array('tl_page'),
            'searchIn' => array('title', 'pageTitle', 'description'),
            'title' => array('title'),
            'prepareDataException' => array( array('PrepareDataException', 'prepareDataExceptions') ),
            'setCustomIcon' => array( array('PrepareDataException', 'setCustomIcon') ),
        ),

        'form' => array(
            'shortcut' => 'fo',
            'icon' => 'form.gif',
            'tables' => array('tl_form', 'tl_form_field'),
            'searchIn' => array('title', 'name'),
            'title' => array('title', 'name', 'type'),
        ),

        'member' => array(
            'shortcut' => 'me',
            'icon' => 'member.gif',
            'tables' => array('tl_member'),
            'searchIn' => array('firstname', 'lastname', 'username', 'email', 'company', 'city', 'postal', 'gender', 'dateOfBirth'),
            'title' => array('username', 'email'),
        ),

        'user' => array(
            'shortcut' => 'us',
            'icon' => 'user.gif',
            'tables' => array('tl_user'),
            'searchIn' => array('username', 'name', 'email'),
            'title' => array('username'),
        ),

        'news' => array(
            'shortcut' => 'ne',
            'icon' => 'news.gif',
            'tables' => array('tl_news_archive', 'tl_news', 'tl_news_feed'),
            'searchIn' => array('title', 'subheadline', 'teaser'),
            'title' => array('title'),
        ),

        'calendar' => array(
            'shortcut' => 'ev',
            'icon' => 'system/modules/calendar/assets/icon.gif',
            'tables' => array('tl_calendar', 'tl_calendar_events'),
            'searchIn' => array('title', 'teaser'),
            'title' => array('title'),
        ),

        'files' => array(
            'tables' => array('tl_files'),
            'searchIn' => array('name', 'meta'),
            'title' => array('name'),
            'prepareDataException' => array( array('PrepareDataException', 'prepareDataExceptions') ),
            'setCustomIcon' => array( array('PrepareDataException', 'setCustomIcon') ),
            'setCustomShortcut' => array( array('PrepareDataException', 'setCustomShortcut') ),
        ),

        'comments' => array(
            'shortcut' => 'co',
            'icon' => 'system/modules/comments/assets/icon.gif',
            'tables' => array('tl_comments'),
            'searchIn' => array('name', 'comment'),
            'title' => array('name'),
        ),

        'newsletter' => array(

            'tables' => array('tl_newsletter', 'tl_newsletter_recipients'),
            'searchIn' => array('subject', 'email'),
            'title' => array('subject', 'email'),
            'setCustomShortcut' => array( array('PrepareDataException', 'setCustomShortcut') ),
            'setCustomIcon' => array( array('PrepareDataException', 'setCustomIcon') ),
        ),

        'faq' => array(
            'shortcut' => 'fq',
            'icon' => 'system/modules/faq/assets/icon.gif',
            'tables' => array('tl_faq_category', 'tl_faq'),
            'searchIn' => array('question', 'title', 'headline'),
            'title' => array('question', 'title'),
        ),

        'prosearch_content' => array(
            'shortcut' => 'ce',
            'icon' => 'alias.gif',
            'tables' => array('tl_content'),
            'searchIn' => array('headline', 'ps_title'),
            'title' => array('ps_title'),
            'prepareDataException' => array( array('PrepareDataException', 'prepareDataExceptions') ),
        ),

        'themes' => array(
            'tables' => array('tl_module', 'tl_layout', 'tl_style_sheet', 'tl_style', 'tl_image_size'),
            'searchIn' => array('name', 'type', 'selector'),
            'title' => array('name', 'selector'),
            'setCustomShortcut' => array( array('PrepareDataException', 'setCustomShortcut') ),
            'setCustomIcon' => array( array('PrepareDataException', 'setCustomIcon') ),
        )

    );

    /**
     * @return array
     * load all searchable modules
     */
    public function loadModules()
    {
		
		// 
        $return = array();

        // set core modules
        $coreModules = $this->coreModules;

        // push dca' into $searchDataContainerArr
        foreach ($coreModules as $tablename => $coreModule) {

            // load dca
            $this->loadDataContainer($tablename);

            // break up if dca not exist
            if (!$GLOBALS['TL_DCA'][$tablename]) {
                continue;
            }

            $return[$tablename] = $coreModule.' ['.$tablename.']';

        }

        return $return;
    }

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCoreModules();
    }

    /**
     *
     */
    public function setCoreModules()
    {
        foreach($this->modules as $k => $module)
        {
            $label = $GLOBALS['TL_LANG']['MOD'][$k];
            $this->modules[$k]['label'] = $label;

            foreach($module['tables'] as $table)
            {
                $this->coreModules[$table] = $label[0] ? $label[0] : '[-]';
            }
        }
    }

    /**
     *
     */
    public function deleteModulesFromIndex()
    {

        $activeModules = deserialize(Config::get('searchIndexModules')) ? deserialize(Config::get('searchIndexModules')) : array();
        $coreModulesArr = Helper::pluckModules($this->coreModules);
        $toDeleteArr = array_diff($coreModulesArr, $activeModules);

        $i = 0;
        $whereStr = '';

        foreach($toDeleteArr as $key => $value)
        {
            if($i == 0)
            {
                $whereStr = 'WHERE dca = "'.$value.'"';

            }else{

                $whereStr .= ' OR dca = "'.$value.'"';
            }

            $i++;
        }

        $this->Database->prepare('DELETE FROM tl_prosearch_data '.$whereStr.'')->execute();

    }

    /**
     * @param $strName
     */
    public function createOnSubmitCallback($strName)
    {

        $coreModulesArr = Helper::pluckModules($this->coreModules);

        if( in_array( $strName, $coreModulesArr ) && $GLOBALS['TL_DCA'][$strName] )
        {
            $GLOBALS['TL_DCA'][$strName]['config']['onsubmit_callback'][] = array('ProSearch', 'sendDataToIndex');
            $GLOBALS['TL_DCA'][$strName]['config']['oncut_callback'][] = array('ProSearch', 'sendDataToIndex');
            $GLOBALS['TL_DCA'][$strName]['config']['ondelete_callback'][] = array('ProSearch', 'deleteDataFromIndex');
        }
    }

    /**
     * @param $dc
     */
    public function sendDataToIndex($dc)
    {
        // current table
        $tablename = $dc->table;

        // current data
        $dcaArr = $dc->activeRecord ? $dc->activeRecord->row() : array();
				
        // get act
        $act = Input::get('act');

        // if cut
        if($act && $act == 'cut')
        {
            $id = Input::get('id');
            $dcaArr = $this->Database->prepare('SELECT * FROM '.$tablename.' WHERE id = ?')->execute($id)->row();

        }
        //
        $arr = array();
        $data = $this->prepareIndexData($dcaArr, $GLOBALS['TL_DCA'][$tablename], $tablename);
		
        if($data == false)
        {
            return;
        }
        $arr[] = $data;

        //
        $newIndexData = $this->fillNewIndexWithExistData($arr);

        //save data
        $this->saveSingleIndexIntoDB($newIndexData, $tablename);

    }

    /**
     *
     */
    public function getDo($stable)
    {
	    
	   if($stable || $stable != '')
	   {		  
	       foreach($this->modules as $do => $module)
	       {
	           foreach($module['tables'] as $table)
	           {
	               if($stable == $table)
	               {
	                   return $do;
	               }
	           }
	       }
       
	   }
	   
       return '';
    }

    /**
     * @param $doTable
     * @return string
     */
    public function getIcon($doTable)
    {
        $icon = '';

        if( $this->modules[$doTable] && $this->modules[$doTable]['icon'] )
        {
            $icon = Image::getHtml($this->modules[$doTable]['icon']);
        }
        return $icon;
    }

    /**
     * @param $db
     * @param $dca
     * @param $do
     * @return array|bool
     */
    public function prepareIndexData($db, $dca, $table)
    {

        // create do string
        $doStr = $this->getDo($table);
        $doTable = $doStr != '' ? $doStr : '';

        // break up if data has no id
        if( !$db['id'] || !$table || $doTable == '')
        {
            return false;
        }

        // set config
        $arr = array(
            'dca' => $table,
            'tstamp' => time(),
            'doTable' => $doTable,
            'ptable' => $dca['config']['ptable'] ? $dca['config']['ptable'] : '',
            'ctable' => $dca['config']['ctable'] ? serialize($dca['config']['ctable']) : '',
            'docId' => $db['id'],
            'pid' => $db['pid'] ? $db['pid'] : '',
            'chmod' => $db['chmod'] ? $db['chmod'] : '',
        );

        /**
         * set shortcut
         */
        $shortcut = '';
        if($this->modules[$doTable])
        {
            $shortcut = $this->modules[$doTable]['shortcut'] ? $this->modules[$doTable]['shortcut'] : '';
        }
        $arr['shortcut'] = $shortcut;
        if( $this->modules[$doTable] && is_array($this->modules[$doTable]['setCustomShortcut']) )
        {
            foreach($this->modules[$doTable]['setCustomShortcut'] as $callable)
            {
                $arr['shortcut'] = call_user_func( array( $callable[0], $callable[1] ), $table, $db, $arr, $dca );
            }
        }

        /**
         * set custom icon callbacks
         */
        if( $this->modules[$doTable] && is_array($this->modules[$doTable]['setCustomIcon']))
        {
            foreach($this->modules[$doTable]['setCustomIcon'] as $callable)
            {
                $this->modules[$doTable]['icon'] = call_user_func( array( $callable[0], $callable[1] ), $table, $db, $arr, $dca );
            }

        }

        //add lang if there
        //$detailField = $this->getDetailField($doTable);
        //$arr['detail'] = $db[$detailField] ? $db[$detailField] : '';

        //add icon
        $arr['icon'] = $this->getIcon($doTable);

        /**
         * exception callbacks
         */
        if( $this->modules[$doTable] && is_array($this->modules[$doTable]['prepareDataException']))
        {
            foreach($this->modules[$doTable]['prepareDataException'] as $callable)
            {
                
                $pDoTable = $this->getDo($db['ptable']);    
                $arr = call_user_func( array( $callable[0], $callable[1] ), $arr, $db, $table, $pDoTable );
            }

        }

        // set type
        $sType = $this->setType($db);
        $arr['type'] = $sType ? $sType : '';

        // set search content
        $sCntent = $this->setSearchContent($db, $arr['doTable']);
        $arr['search_content'] = $sCntent ? $sCntent : '';

        $sTitle = $this->setTitle($db, $arr['doTable']);
        // set title
        $arr['title'] = $sTitle ? $sTitle : 'no title';

        return $arr;
    }

    /**
     *
     */
    /*
    public function getDetailField($doTable)
    {
        $detail = '';

        if( $this->modules[$doTable] && $this->modules[$doTable]['detail'] )
        {
            $detail = $this->modules[$doTable]['detail'];
        }

        return $detail;
    }
    */

    /**
     * @param $db
     * @return string|void
     */
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

        return null;

    }

    /**
     * @param $db
     * @return string|void
     */
    public function setSearchContent($db, $doTable)
    {

        if(!$doTable && $doTable == '')
        {
            return '';
        }

        $colsSearchContent = $this->modules[$doTable]['searchIn'];
        $colsSearchContent = $colsSearchContent ? $colsSearchContent : array();

        $strContent = '';

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
        $strContent = mb_strtolower($strContent);

        return $strContent;

    }

    /**
     * @param $db
     */
    public function setTitle($db, $doTable)
    {
        // sorted by priority
        if(!$doTable && $doTable == '')
        {
            return null;
        }

        $colsForTitle = $this->modules[$doTable]['title'];
        $colsForTitle = $colsForTitle ? $colsForTitle : array();

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

        return null;
    }


    /**
     * @param $searchDataDB
     * @param $arr
     * @return array
     */
    public function fillNewIndexWithExistData($arr)
    {
        for($i = 0; $i < count($arr); $i++)
        {
            $doTable = $arr[$i]['doTable'];
            $docId = $arr[$i]['docId'];
            $searchIndexDB = $this->Database->prepare('SELECT * FROM tl_prosearch_data WHERE docID = ? AND doTable = ?')->execute($docId, $doTable);
            while($searchIndexDB->next())
            {
                $arr[$i]['id'] = $searchIndexDB->id;
            }
        }

        return $arr;
    }

    /**
     * @param $indexData
     */
    public function saveIndexDataIntoDB($data,$dca, $page = 0)
    {
        //reset table
        if($page == 0)
        {
            $this->clearSearchIndexTable($dca);
        }

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


    public function clearSearchIndexTable($dca)
    {
        $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE dca = ?')->execute($dca);
    }

    /**
     * @param $data
     * @param $dca
     */
    public function saveSingleIndexIntoDB($data, $dca)
    {

        foreach($data as $arr)
        {

            if( !$arr['docId'] || !$arr['docId'] != '' )
            {
                continue;
            }

            $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE dca = ? AND docId = ?')->execute($dca, $arr['docId']);

            // values
            $values = array_values($arr);
            $placeholder = implode(',', array_fill(0,count($values), '?'));

            // cols
            $cols = array_keys($arr);
            $cols = implode(',',$cols);

            $this->Database->prepare('INSERT INTO tl_prosearch_data('.$cols.') VALUES ('.$placeholder.')')->execute($values);

        }

    }

    /**
     * @param $dca
     */
    public function deleteDataFromIndex($dc)
    {

        $tablename = $dc->table;
        $docId = $dc->activeRecord->id;

        // files exception
        if(Input::get('do') == 'files')
        {
            $tablename = 'tl_files';
            $docId = $dc;
        }

        if( !$tablename )
        {
            return;
        }

        if( !$docId )
        {
            return;
        }

        //delete parent
        $pDataDB = $this->Database->prepare('SELECT * FROM tl_prosearch_data WHERE dca = ? AND docId = ?')->execute($tablename, $docId);

        while($pDataDB->next())
        {
            $this->deletedIndexData[] = $pDataDB->row();
        }


        //delete all childs
        $this->deleteChildrenFromIndex($tablename, $docId);
        $this->clearIndex();

    }

    /**
     * @param $ptable
     * @param $pid
     */
    public function deleteChildrenFromIndex($ptable, $pid)
    {

        $cDataDB = $this->Database->prepare('SELECT * FROM tl_prosearch_data WHERE ptable = ? AND pid = ?')->execute($ptable, $pid);

        if( $cDataDB->count() > 0 )
        {
            while( $cDataDB->next() )
            {

                $table = $cDataDB->dca;
                $id = $cDataDB->docId;
                $activeModules = deserialize(Config::get('searchIndexModules'));
                $ctables = deserialize($cDataDB->ctable);


                if( in_array( $table, $activeModules ) )
                {
                    $this->deleteChildrenFromIndex($table, $id);
                }


                if( is_array($ctables) )
                {
                    foreach($ctables as $ctable)
                    {
                        if( in_array($ctable, $activeModules) )
                        {
                            $this->deleteChildrenFromIndex($ctable, $id);
                        }
                    }
                }

                $this->deletedIndexData[] = $cDataDB->row();

            }

        }
    }

    /**
     *
     */
    public function clearIndex()
    {
        foreach($this->deletedIndexData as $indexData)
        {
            $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE id = ?')->execute($indexData['id']);
        }
    }

    /**
     *
     */
    public function ajaxRequest()
    {
        if( Input::get('ajaxRequestForProSearch') && Input::get('ajaxRequestForProSearch') == 'getSearchIndex' )
        {
            // query
            $q = Input::get('searchQuery');

            // settings @todo

            // header information
            $header = array(
                'q' => $q ? $q : ''
            );

            $results = array();
            $results['response'] = $this->getSearchDataFromIndex($header);

            // get shortcut labels
            $results['shortcut_labels'] = array();
            $this->loadLanguageFile('tl_prosearch_data');
            $results['shortcut_labels'] = $GLOBALS['TL_LANG']['tl_prosearch_data']['shortcut'];

            // send response
            header('Content-type: application/json');
            echo json_encode($results);
            exit;
        }
    }

    /**
     * @param $header
     * @return array
     */
    public function getSearchDataFromIndex($header)
    {

		// check for shortcuts
		$q = $header['q'];
		$shortcutAndQ = explode(':', $q);
        $docLimit = 250;
        $limit = 5;

        // import
        $this->import('BackendUser', 'User');

        $isAdmin = $this->User->isAdmin;

        $permArr = array(
            'modules' => $this->User->modules ?  $this->User->modules : array(),
            'allowedPageTypes' => $this->User->alpty,
            'pagemounts' => $this->User->pagemounts ? $this->User->pagemounts : array(),
            'groups' => $this->User->groups ? $this->User->groups: '0'
        );

        //shortcut query Str
        $shortcutSqlStr = '';

        //lang query str
        $langSqlStr = '';

        // Ohne Shortcut
        if(count($shortcutAndQ) == 1)
        {
            $q = $shortcutAndQ[0];
            if(strlen($q) > 4)
            {
                $docLimit = 500;
            }
        }

        // Mit Shortcut
        if(count($shortcutAndQ) > 1)
        {
            $shortcut = $shortcutAndQ[0];
            $q = $shortcutAndQ[1];
            $docLimit = 500;
            $limit = 50;
            $shortcutSqlStr = 'AND shortcut = "'.$shortcut.'" ';
        }

        // Mit Shortcut und Sprache
        if(count($shortcutAndQ) > 2)
        {
            $lang = $shortcutAndQ[1];
            $q = $shortcutAndQ[2];
            $docLimit = 1000;
            $limit = 50;
            $langSqlStr = 'AND language = "'.$lang.'"';
        }

        if(!$q)
        {
           return array();
        }

        // put all results
        $searchResultsContainerGroup = array();

        // get top
        $lastUpdateDB = $this->Database->prepare("SELECT * FROM tl_prosearch_data WHERE search_content LIKE ? ".$shortcutSqlStr.$langSqlStr." ORDER BY tstamp DESC LIMIT 3")->execute("%$q%");

        //
        $dataDB = $this->Database->prepare(

            "SELECT * FROM tl_prosearch_data WHERE search_content LIKE ? ".$shortcutSqlStr.$langSqlStr." "
            ."ORDER BY "
            ."CASE "
            ."WHEN (LOCATE(?, search_content) = 0) THEN 10 "  // 1 "Köl" matches "Kolka" -> sort it away
            ."WHEN title = ? THEN 1 "                // 2 "word"     Sortier genaue Matches nach oben ( Berlin vor Berlingen für "Berlin")
            ."WHEN title LIKE ? THEN 2 "             // 3 "word "    Sortier passende Matches nach oben ( "Berlin Spandau" vor Berlingen für "Berlin")
            ."WHEN title LIKE ? THEN 3 "             // 4 "word%"    Sortier Anfang passt
            ."WHEN title LIKE ? THEN 4 "             // 4 "%word"    Sortier Ende passt
            ."WHEN title LIKE ? THEN 5 "             // 5 "%word%"   Irgendwo getroffen
            ."ELSE 6 "  //whatever
            ."END "
            ."LIMIT ".$docLimit.""

        )->execute("%$q%", $q, $q, "$q %", "%$q", "$q%", "%$q%");


        // parse
        while($lastUpdateDB->next())
        {
            $searchItem = $lastUpdateDB->row();

            if(!$isAdmin)
            {
                if( !$this->checkPermission($permArr, $searchItem) )
                {
                    //continue;
                }
            }

            $searchItem['buttonsStr'] = $this->addButtonStr($searchItem, $isAdmin, $permArr);

            $searchResultsContainerGroup['top'][] = $searchItem;

        }
        while($dataDB->next())
        {

            $searchItem = $dataDB->row();

            if(!$isAdmin)
            {
                if( !$this->checkPermission($permArr, $searchItem) )
                {
                    //continue;
                }
            }

            $searchItem['buttonsStr'] = $this->addButtonStr($searchItem, $isAdmin, $permArr);

            if(count($searchResultsContainerGroup[$searchItem['shortcut']]) <= $limit)
            {
                $searchResultsContainerGroup[$searchItem['shortcut']][] = $searchItem;
            }
        }

        return $searchResultsContainerGroup;

    }

    /**
     * @param $searchItem
     * @param $admin
     * @param $permArr
     * @return string
     */
    public function addButtonStr($searchItem, $admin, $permArr)
    {
        return $this->createButtons($searchItem, $admin, $permArr);
    }

    /**
     * @param $permArr
     * @param $searchItem
     * @return bool
     */
    public function checkPermission($permArr, $searchItem)
    {
        if(in_array( $searchItem['doTable'], $permArr['modules'] ) )
        {
            return call_user_func(array('CheckPermission', 'checkPermission'), $searchItem['doTable'], $permArr, $searchItem);
        }
        return false;

    }
}