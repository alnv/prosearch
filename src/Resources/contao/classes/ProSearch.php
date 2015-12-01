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


use Contao\Backend;
use Contao\Config;

/**
 * Class ProSearch
 */
class ProSearch extends Backend
{

    public $coreModules = array('tl_article', 'tl_page', 'tl_form', 'tl_member', 'tl_user', 'tl_news', 'tl_calendar', 'tl_files', 'tl_comments', 'tl_newsletter', 'tl_faq', 'tl_content', 'tl_module');

    /**
     * @return array
     * load all searchable modules
     */
    public function loadModules()
    {

        $return = array();

        // set core modules
        $coreModules = $this->coreModules;

        // push dca' into $searchDataContainerArr
        foreach ($coreModules as $coreModule) {

            // set tablename
            $tablename = $coreModule;

            // load dca
            $this->loadDataContainer($tablename);

            // break up if dca not exist
            if (!$GLOBALS['TL_DCA'][$tablename]) {
                continue;
            }

            $return[] = $tablename;

        }

        return $return;
    }

    /**
     *
     */
    public function deleteModulesFromIndex()
    {
        $activeModules = deserialize(Config::get('searchIndexModules')) ? deserialize(Config::get('searchIndexModules')) : array();

        $toDeleteArr = array_diff($this->coreModules, $activeModules);

        $whereStr = 'WHERE dca = "'.$toDeleteArr[0].'"';

        if(count($toDeleteArr) > 1)
        {
            foreach($toDeleteArr as $key => $value)
            {
                if($key == 0)
                {
                    continue;
                }

                $whereStr .= ' OR dca = "'.$value.'"';
            }
        }

        $this->Database->prepare('DELETE FROM tl_prosearch_data '.$whereStr.'')->execute();

    }

}