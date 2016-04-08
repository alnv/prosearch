<?php namespace ProSearch;

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2016 Alexander Naumov
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
    public $notCoreModules = array('faq', 'newsletter', 'comments', 'calendar', 'news');
    public $deletedIndexData = array();
    public $modules = array(

        'article' => array(
            'shortcut' => 'ar',
            'icon' => 'article.gif',
            'tables' => array('tl_article'),
            'searchIn' => array('title'),
            'title' => array('title')
        ),

        'page' => array(
            'shortcut' => 'pa',
            'tables' => array('tl_page'),
            'searchIn' => array('title', 'pageTitle', 'description', 'id'),
            'title' => array('title'),
            'prepareDataException' => array(array('PrepareDataException', 'prepareDataExceptions')),
            'setCustomIcon' => array(array('PrepareDataException', 'setCustomIcon')),
        ),

        'form' => array(
            'shortcut' => 'fo',
            'icon' => 'form.gif',
            'tables' => array('tl_form', 'tl_form_field'),
            'searchIn' => array('title', 'name', 'id'),
            'title' => array('title', 'name', 'id', 'type'),
        ),

        'member' => array(
            'shortcut' => 'me',
            'icon' => 'member.gif',
            'tables' => array('tl_member'),
            'searchIn' => array('firstname', 'lastname', 'username', 'email', 'company', 'city', 'postal', 'gender', 'dateOfBirth'),
            'title' => array('username', 'email'),
        ),

        'mgroup' => array(
            'shortcut' => 'mg',
            'icon' => 'mgroup.gif',
            'tables' => array('tl_member_group'),
            'searchIn' => array('name'),
            'title' => array('name'),
        ),

        'user' => array(
            'shortcut' => 'us',
            'icon' => 'user.gif',
            'tables' => array('tl_user'),
            'searchIn' => array('username', 'name', 'email'),
            'title' => array('username'),
        ),

        'group' => array(
            'shortcut' => 'ug',
            'icon' => 'group.gif',
            'tables' => array('tl_user_group'),
            'searchIn' => array('name'),
            'title' => array('name'),
        ),

        'news' => array(
            'shortcut' => 'ne',
            'icon' => 'news.gif',
            'tables' => array('tl_news_archive', 'tl_news', 'tl_news_feed'),
            'searchIn' => array('title', 'subheadline', 'teaser'),
            'title' => array('headline', 'title'),
        ),

        'calendar' => array(
            'shortcut' => 'ev',
            'icon' => 'event.gif',
            'tables' => array('tl_calendar', 'tl_calendar_events'),
            'searchIn' => array('title', 'teaser'),
            'title' => array('title'),
        ),

        'files' => array(
            'tables' => array('tl_files'),
            'searchIn' => array('name', 'meta'),
            'title' => array('name'),
            'prepareDataException' => array(array('PrepareDataException', 'prepareDataExceptions')),
            'setCustomIcon' => array(array('PrepareDataException', 'setCustomIcon')),
            'setCustomShortcut' => array(array('PrepareDataException', 'setCustomShortcut')),
            'setCustomTitle' => array(array('PrepareDataException', 'setCustomTitle')),
        ),

        'comments' => array(
            'shortcut' => 'co',
            'icon' => 'comment.gif',
            'tables' => array('tl_comments'),
            'searchIn' => array('name', 'comment'),
            'title' => array('name'),
        ),

        'newsletter' => array(
            'tables' => array('tl_newsletter', 'tl_newsletter_recipients'),
            'searchIn' => array('subject', 'email'),
            'title' => array('subject', 'email'),
            'setCustomShortcut' => array(array('PrepareDataException', 'setCustomShortcut')),
            'setCustomIcon' => array(array('PrepareDataException', 'setCustomIcon')),
        ),

        'faq' => array(
            'shortcut' => 'fq',
            'icon' => 'faq.gif',
            'tables' => array('tl_faq_category', 'tl_faq'),
            'searchIn' => array('question', 'title', 'headline'),
            'title' => array('question', 'title'),
        ),

        'ps_content' => array(
            'shortcut' => 'ce',
            'icon' => 'alias.gif',
            'tables' => array('tl_content'),
            'searchIn' => array('headline', 'id', 'type', 'title', 'alt'),
            'title' => array('headline', 'title', 'alt', 'id'),
            'useParentAsBE' => true, // tl_content has no backend modul
            'prepareDataException' => array(array('PrepareDataException', 'prepareDataExceptions')),
            'setCustomTitle' => array(array('PrepareDataException', 'setCustomTitle')),
        ),

        'themes' => array(
            'tables' => array('tl_module', 'tl_layout', 'tl_style_sheet', 'tl_style', 'tl_image_size'),
            'searchIn' => array('name', 'type', 'selector'),
            'title' => array('name', 'selector'),
            'setCustomShortcut' => array(array('PrepareDataException', 'setCustomShortcut')),
            'setCustomIcon' => array(array('PrepareDataException', 'setCustomIcon')),
        )

    );


    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->initProSearch();

    }

    /**
     * load tables
     */
    public function initProSearch()
    {
        // set Vendor Modules
        if ($GLOBALS['PS_SEARCHABLE_MODULES'] && is_array($GLOBALS['PS_SEARCHABLE_MODULES']) && !empty($GLOBALS['PS_SEARCHABLE_MODULES'])) {
            foreach ($GLOBALS['PS_SEARCHABLE_MODULES'] as $modname => $module) {
                if (is_array($module) && !empty($module)) {
                    $this->modules[$modname] = $module;
                }
            }
        }


        // set global
        $GLOBALS['PS_SEARCHABLE_MODULES'] = $this->modules;

        // set f modules if fmodule installed
        $this->setFModules();

        // set core tables
        $this->setCoreModules();
    }

    /**
     *
     */
    public function setFModules()
    {
        if ($GLOBALS['PS_SEARCHABLE_MODULES'] && $GLOBALS['PS_SEARCHABLE_MODULES']['fmodule'] && is_array($GLOBALS['PS_SEARCHABLE_MODULES']['fmodule'])) {

            $modulesDB = $this->Database->prepare('SELECT * FROM tl_fmodules')->execute();

            while ($modulesDB->next()) {

                if (!$modulesDB->tablename) continue;

                $strTableCount = strlen($modulesDB->tablename);
                $doTable = substr($modulesDB->tablename, 3, $strTableCount);
                $wrapperTable = $modulesDB->tablename;
                $dataTable = $modulesDB->tablename . '_data';

                $icon = 'files/fmodule/assets/' . $wrapperTable . '_icon.png';

                if (!file_exists(TL_ROOT . '/' . $icon)) {
                    $this->notCoreModules[] = $doTable;
                    $icon = 'fmodule.png';

                }

                $shortcut = 'fm';

                if ($modulesDB->ps_shortcut) {
                    $shortcut = $modulesDB->ps_shortcut;
                    $GLOBALS['TL_LANG']['tl_prosearch_data']['shortcut'][$modulesDB->ps_shortcut] = $modulesDB->name;
                }

                $this->modules[$doTable] = array(
                    'shortcut' => $shortcut,
                    'icon' => $icon,
                    'tables' => array($wrapperTable, $dataTable),
                    'searchIn' => array('title', 'info', 'description'),
                    'title' => array('title'),
                );
            }

            $GLOBALS['PS_SEARCHABLE_MODULES'] = $this->modules;

        }
    }

    /**
     * @return array
     * load all searchable modules
     */
    public function loadModules()
    {

        $this->initProSearch();

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

            $return[$tablename] = $coreModule . ' [' . $tablename . ']';

        }

        return $return;
    }


    /**
     *
     */
    public function setCoreModules()
    {

        foreach ($this->modules as $k => $module) {

            $label = $GLOBALS['TL_LANG']['MOD'][$k];
            $this->modules[$k]['label'] = $label;

            foreach ($module['tables'] as $table) {
                $this->coreModules[$table] = $label[0] ? $label[0] : '[no-label-found]';
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

        foreach ($toDeleteArr as $key => $value) {
            if ($i == 0) {
                $whereStr = 'WHERE dca = "' . $value . '"';

            } else {

                $whereStr .= ' OR dca = "' . $value . '"';
            }

            $i++;
        }

        if (!$whereStr) {
            return null;
        }

        $this->Database->prepare('DELETE FROM tl_prosearch_data ' . $whereStr . '')->execute();

    }

    /**
     * @param $strName
     */
    public function createOnSubmitCallback($strName)
    {

        $coreModulesArr = Helper::pluckModules($this->coreModules);

        if (in_array($strName, $coreModulesArr) && $GLOBALS['TL_DCA'][$strName]) {
            $GLOBALS['TL_DCA'][$strName]['config']['onsubmit_callback'][] = array('ProSearch', 'sendDataToIndex');
            $GLOBALS['TL_DCA'][$strName]['config']['oncut_callback'][] = array('ProSearch', 'sendDataToIndex');
            $GLOBALS['TL_DCA'][$strName]['config']['ondelete_callback'][] = array('ProSearch', 'deleteDataFromIndex');
        }
    }

    /**
     * @param $dc
     */
    public function sendDataToIndex($dc, $value = '')
    {
        // current table
        $tablename = $dc->table;

        // current data
        $dcaArr = $dc->activeRecord ? $dc->activeRecord->row() : array();

        // col
        $colname = 'id';

        // get act
        $act = Input::get('act');

        // if cut
        if ($act && $act == 'cut') {

            $id = Input::get('id');

            if(Input::get('do') == 'files')
            {
                $tablename = 'tl_files';
                $id = $value;
                $colname = 'path';
            }

            if(!$tablename)
            {
                return;
            }

            $dcaArr = $this->Database->prepare('SELECT * FROM ' . $tablename . ' WHERE '.$colname.' = ?')->execute($id)->row();

        }

        //
        $arr = array();

        $data = $this->prepareIndexData($dcaArr, $GLOBALS['TL_DCA'][$tablename], $tablename);

        if ($data == false) {
            return;
        }

        $arr[] = $data;

        $newIndexData = $arr;
        $this->saveSingleIndexIntoDB($newIndexData, $tablename);

    }

    /**
     * @param $doTable
     * @return string
     */
    public function getIcon($doTable)
    {
        $icon = '';

        $path = 'system/modules/prosearch/assets/images/';

        if ((version_compare(VERSION, '4.0', '>=') && !$GLOBALS['PS_NO_COMPOSER'] && $GLOBALS['PS_NO_COMPOSER'] != true)) {
            $path = 'bundles/prosearch/images/';
        }

        if (!in_array($doTable, $this->notCoreModules)) {
            $path = '';
        }

        if ($this->modules[$doTable] && $this->modules[$doTable]['icon']) {

            $icon = Image::getHtml($path . $this->modules[$doTable]['icon']);

        }

        return $icon;
    }

    /**
     * @param $db
     * @param $dca
     * @param $table
     * @return array|bool
     */
    public function prepareIndexData($db, $dca, $table)
    {

        // create do string
        $doStr = Helper::getDoParam($table);
        $doTable = $doStr != '' ? $doStr : '';

        //
        if (!$db['id'] || !$table || !$doTable) {
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
            'pid' => isset($db['pid']) ? $db['pid'] : 0,
            'extension' => $db['extension'] ? $db['extension'] : '',
            'tags' => $db['ps_tags'] ? $db['ps_tags'] : '',
            'blocked' => $db['ps_block_item'] ? $db['ps_block_item'] : '',
            'blocked_ug' => $db['ps_block_usergroup'] ? $db['ps_block_usergroup'] : ''
        );


        /**
         * set shortcut
         */
        $shortcut = '';
        if ($this->modules[$doTable]) {
            $shortcut = $this->modules[$doTable]['shortcut'] ? $this->modules[$doTable]['shortcut'] : '';
        }
        $arr['shortcut'] = $shortcut;
        if ($this->modules[$doTable] && is_array($this->modules[$doTable]['setCustomShortcut'])) {

            foreach ($this->modules[$doTable]['setCustomShortcut'] as $callable) {

                $this->import($callable[0]);
                $arr['shortcut'] = $this->{$callable[0]}->{$callable[1]}($table, $db, $arr, $dca);

            }

        }

        /**
         * set custom icon callbacks
         */
        if ($this->modules[$doTable] && is_array($this->modules[$doTable]['setCustomIcon'])) {

            foreach ($this->modules[$doTable]['setCustomIcon'] as $callable) {


                $this->import($callable[0]);
                $this->modules[$doTable]['icon'] = $this->{$callable[0]}->{$callable[1]}($table, $db, $arr, $dca);

            }

        }

        //add icon
        $arr['icon'] = $this->getIcon($doTable);

        /**
         * exception callbacks
         */
        if ($this->modules[$doTable] && is_array($this->modules[$doTable]['prepareDataException'])) {

            foreach ($this->modules[$doTable]['prepareDataException'] as $callable) {

                $this->import($callable[0]);
                $arr = $this->{$callable[0]}->{$callable[1]}($arr, $db, $table);

            }

        }

        // set type
        $sType = $this->setType($db);
        $arr['type'] = $sType ? $sType : '';

        // set search content
        $sCntent = $this->setSearchContent($db, $arr['doTable']);
        $arr['search_content'] = $sCntent ? $sCntent : '';

        $sTitle = $this->setTitle($db, $arr['doTable'], $table);

        // set title
        $arr['title'] = $sTitle ? $sTitle : 'no title';

        // add be module dyn if useParentAsBE is true
        if ($this->modules[$doTable]['useParentAsBE']) {
            $do = Helper::getDoParam($arr['ptable']);
            $arr['doTable'] = $do;

            if (!$arr['doTable']) {
                return false;
            }

        }

        return $arr;
    }

    /**
     * @param $db
     * @return null|string
     */
    public function setType($db)
    {
        $colsForTypes = array('type');

        foreach ($colsForTypes as $type) {
            if ($db[$type] && is_string($db[$type]) && $db[$type] != '') {
                $meta = Helper::parseStrForMeta($db[$type]);
                $return = $meta != '' ? ' ' . $meta : ' ' . $db[$type];
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

        if (!$doTable) {
            return '';
        }

        $colsSearchContent = $this->modules[$doTable]['searchIn'];

        array_unshift($colsSearchContent, 'ps_search_content');
        $colsSearchContent = $colsSearchContent ? $colsSearchContent : array();

        // addDescriptionToSearchContent
        $textCols = array('text', 'description');
        if (Config::get('addDescriptionToSearchContent'))
        {
            foreach($textCols as $col)
            {
                if(in_array($col, $db))
                {
                    $colsSearchContent[] = $col;
                }
            }
        }

        $strContent = '';

        foreach ($colsSearchContent as $content) {

            if(!$db[$content])
            {
                continue;
            }

            $ct = deserialize($db[$content]);

            if (is_array($ct) && !empty($ct)) {
                $meta = Helper::parseStrForMeta($db[$content]);
                $strContent .= $meta;
                continue;

            }


            if ($db[$content] && (is_string($db[$content]) || is_numeric($db[$content]))) {

                $strContent .= ' ' . $db[$content];
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
     * @param $doTable
     * @param $table
     * @return null|string
     */
    public function setTitle($db, $doTable, $table)
    {
        // sorted by priority
        if (!$doTable) {
            return null;
        }

        $colsForTitle = $this->modules[$doTable]['title'];
        array_unshift($colsForTitle, 'ps_title');
        $colsForTitle = $colsForTitle ? $colsForTitle : array();

        // hook for custom title
        if ($this->modules[$doTable] && is_array($this->modules[$doTable]['setCustomTitle'])) {

            foreach ($this->modules[$doTable]['setCustomTitle'] as $callable) {


                $this->import($callable[0]);
                return $this->{$callable[0]}->{$callable[1]}($table, $db, $colsForTitle, $doTable);

            }

        }

        foreach ($colsForTitle as $title) {

            if(!$db[$title])
            {
                continue;
            }
            
            $ct = deserialize($db[$title]);

            // check if value is serialize
            if (is_array($ct) && !empty($ct)) {
                $meta = Helper::parseStrForMeta($db[$title]);
                $db[$title] = $meta;
            }

            if ($db[$title] && (is_string($db[$title]) || is_numeric($db[$title]))) {

                $return = $db[$title];
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
     * @param $data
     * @param $dca
     * @param int $page
     */
    public function saveIndexDataIntoDB($data, $dca, $page = 0)
    {
        //reset table
        if ($page == 0) {
            $this->clearSearchIndexTable($dca);
        }

        // insert new cols
        foreach ($data as $arr) {

            // values
            $values = array_values($arr);
            $placeholder = implode(',', array_fill(0, count($values), '?'));

            // cols
            $cols = array_keys($arr);
            $cols = implode(',', $cols);

            // db operations
            $this->Database->prepare('INSERT INTO tl_prosearch_data(' . $cols . ') VALUES (' . $placeholder . ')')->execute($values);

        }
    }

    /**
     * @param $dca
     * @return null
     */
    public function clearSearchIndexTable($dca)
    {
        if (!$dca) {
            return null;
        }

        $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE dca = ?')->execute($dca);
    }

    /**
     * @param $data
     * @param $dca
     */
    public function saveSingleIndexIntoDB($data, $dca)
    {

        foreach ($data as $arr) {

            if (!$arr['docId'] || !$arr['docId'] != '') {
                continue;
            }

            $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE dca = ? AND docId = ?')->execute($dca, $arr['docId']);

            // values
            $values = array_values($arr);
            $placeholder = implode(',', array_fill(0, count($values), '?'));

            // cols
            $cols = array_keys($arr);
            $cols = implode(',', $cols);

            $this->Database->prepare('INSERT INTO tl_prosearch_data(' . $cols . ') VALUES (' . $placeholder . ')')->execute($values);

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
        if (Input::get('do') == 'files') {
            $tablename = 'tl_files';
            $docId = $dc;
        }

        if (!$tablename) {
            return;
        }

        if (!$docId) {
            return;
        }

        //delete parent
        $pDataDB = $this->Database->prepare('SELECT * FROM tl_prosearch_data WHERE dca = ? AND docId = ?')->execute($tablename, $docId);

        while ($pDataDB->next()) {
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

        if ($cDataDB->count() > 0) {
            while ($cDataDB->next()) {

                $table = $cDataDB->dca;
                $id = $cDataDB->docId;
                $activeModules = deserialize(Config::get('searchIndexModules'));
                $ctables = deserialize($cDataDB->ctable);


                if (in_array($table, $activeModules)) {
                    $this->deleteChildrenFromIndex($table, $id);
                }


                if (is_array($ctables)) {
                    foreach ($ctables as $ctable) {
                        if (in_array($ctable, $activeModules)) {
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
        foreach ($this->deletedIndexData as $indexData) {
            $this->Database->prepare('DELETE FROM tl_prosearch_data WHERE id = ?')->execute($indexData['id']);
        }
    }

    /**
     *
     */
    public function ajaxRequest()
    {
        if (Input::get('ajaxRequestForProSearch') && Input::get('ajaxRequestForProSearch') == 'getSearchIndex') {

            // query
            $q = Input::get('searchQuery');

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
            echo json_encode($results, 512);
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
        $docLimit = 500;
        $limit = 10;

        // import
        $this->import('BackendUser', 'User');

        //shortcut query Str
        $shortcutSqlStr = '';

        // Ohne Shortcut
        if (count($shortcutAndQ) == 1) {
            $q = $shortcutAndQ[0];
            if (strlen($q) > 4) {
                $docLimit = 1000;
            }
        }

        // Mit Shortcut
        if (count($shortcutAndQ) > 1) {
            $shortcut = $shortcutAndQ[0];
            $q = $shortcutAndQ[1];
            $docLimit = 1000;
            $limit = 30;

            if ($shortcut == 'tag' || $shortcut == 't') {
                return $this->getSerachDataByTag($q);
            }

            $shortcutSqlStr = 'AND shortcut = "' . $shortcut . '" ';
        }

        if (!$q) {
            return array();
        }

        // put all results
        $searchResultsContainerGroup = array();

        // get top
        $lastUpdateDB = $this->Database->prepare("SELECT * FROM tl_prosearch_data WHERE blocked != '1' AND search_content LIKE ? " . $shortcutSqlStr . " ORDER BY tstamp DESC LIMIT 3")->execute("%$q%");

        //
        $dataDB = $this->Database->prepare(

            "SELECT * FROM tl_prosearch_data WHERE blocked != '1' AND search_content LIKE ? " . $shortcutSqlStr . " "
            . "ORDER BY "
            . "CASE "
            . "WHEN (LOCATE(?, search_content) = 0) THEN 10 "  // 1 "Köl" matches "Kolka" -> sort it away
            . "WHEN title = ? THEN 1 "                // 2 "word"     Sortier genaue Matches nach oben ( Berlin vor Berlingen für "Berlin")
            . "WHEN title LIKE ? THEN 2 "             // 3 "word "    Sortier passende Matches nach oben ( "Berlin Spandau" vor Berlingen für "Berlin")
            . "WHEN title LIKE ? THEN 3 "             // 4 "word%"    Sortier Anfang passt
            . "WHEN title LIKE ? THEN 4 "             // 4 "%word"    Sortier Ende passt
            . "WHEN title LIKE ? THEN 5 "             // 5 "%word%"   Irgendwo getroffen
            . "ELSE 6 "  //whatever
            . "END "
            . "LIMIT " . $docLimit . ""

        )->execute("%$q%", $q, $q, "$q %", "%$q", "$q%", "%$q%");

        // parse
        while ($lastUpdateDB->next()) {

            $searchItem = $lastUpdateDB->row();

            if (!$this->checkPermission($searchItem)) {
                continue;
            }

            $searchItem['buttonsStr'] = $this->addButtonStr($searchItem);
            $searchResultsContainerGroup['top'][] = $searchItem;

        }

        while ($dataDB->next()) {

            $searchItem = $dataDB->row();

            if (!$this->checkPermission($searchItem)) {
                continue;
            }

            $searchItem['buttonsStr'] = $this->addButtonStr($searchItem);

            if (count($searchResultsContainerGroup[$searchItem['shortcut']]) < $limit) {

                $searchResultsContainerGroup[$searchItem['shortcut']][] = $searchItem;

            }
        }

        return $searchResultsContainerGroup;

    }

    /**
     * @param $q
     * @return array
     */
    public function getSerachDataByTag($q)
    {

        if (!$q) {
            return array();
        }

        $tagDB = $this->Database->prepare("SELECT * FROM tl_prosearch_data WHERE blocked != '1' AND tags LIKE ? ORDER BY tstamp DESC LIMIT 500")->execute("%$q%");
        $searchResultsContainerGroup = array();

        while ($tagDB->next()) {

            $searchItem = $tagDB->row();

            if (!$this->checkPermission($searchItem)) {
                continue;
            }

            $searchItem['buttonsStr'] = $this->addButtonStr($searchItem);

            if (count($searchResultsContainerGroup[$searchItem['shortcut']]) <= 250) {
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
    public function addButtonStr($searchItem)
    {
        return $this->createButtons($searchItem);
    }

    /**
     * @param $searchItem
     * @return bool
     */
    private function checkPermission($searchItem)
    {

        $group = $this->User->groups ? $this->User->groups : array();

        if (empty($group)) {
            return true;
        }

        if (!$this->User->isAdmin) {
            $blocked_ug = $searchItem['blocked_ug'] ? $searchItem['blocked_ug'] : array();
            $blocked_ug = deserialize($blocked_ug);

            if (empty($blocked_ug)) {
                return true;
            }

            if (array_intersect($group, $blocked_ug) > 0) {
                return false;
            }

        }

        return true;
    }

}