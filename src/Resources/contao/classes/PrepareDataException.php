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

class PrepareDataException
{
    /**
     *
     */
    public function prepareDataExceptions($arr, $db, $table, $pDoTable)
    {
        // exception for content
        if( $table == 'tl_content')
        {
            $arr['ptable'] = $db['ptable'] ? $db['ptable'] : '';
            $arr['doTable'] = $pDoTable ? $pDoTable : '';
        }

        // exception for page
        if( $table == 'tl_page')
        {
            $arr['ptable'] = 'tl_page';
        }

        // exception for files
        if( $table == 'tl_files' )
        {
            $arr['docId'] = $db['path'] ? $db['path'] : '';
            $arr['pid'] = '';
        }

        return $arr;
    }

    public function setCustomIcon($table)
    {
        $iconName = '';

        if($table == 'tl_module')
        {
            $iconName = 'modules.gif';
        }

        if($table == 'tl_layout')
        {
            $iconName = 'layout.gif';
        }

        return $iconName;

    }

}