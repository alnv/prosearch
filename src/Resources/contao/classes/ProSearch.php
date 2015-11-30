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

/**
 * Class ProSearch
 */
class ProSearch extends Backend
{

    /**
     * @return array
     * load all searchable modules
     */
    public function loadModules()
    {

        $return = array();

        // set core modules
        $coreModules = array(
            'tl_article', 'tl_page', 'tl_form', 'tl_member', 'tl_user', 'tl_news', 'tl_calendar', 'tl_files', 'tl_events', 'tl_comments', 'tl_newsletter', 'tl_faq', 'tl_content', 'tl_module'
        );


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
     * @param $table
     * @return array
     */
    public function createSearchUrls($table)
    {
        //
        $urls = array();

        //

        //
        return $urls;
    }

}