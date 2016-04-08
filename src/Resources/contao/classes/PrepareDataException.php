<?php namespace ProSearch;

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   CC BY-NC-ND 4.0
 * @copyright 2016 Alexander Naumov
 */

/**
 * Class PrepareDataException
 * @package ProSearch
 */
class PrepareDataException
{
    /**
     * @param $arr
     * @param $db
     * @param $table
     * @return mixed
     */
    public function prepareDataExceptions($arr, $db, $table)
    {
        // exception for content
        if( $table == 'tl_content')
        {
            $ptable = $db['ptable'];
            $arr['ptable'] = $ptable ? $ptable : '';

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

    /**
     * @param $table
     * @param $db
     * @param $titleFields
     * @param $doTable
     * @return string
     */
    public function setCustomTitle($table, $db, $titleFields, $doTable)
    {

        if($table == 'tl_files')
        {
            return $db['name'] ? $db['name'] : $db['path'];
        }

        if($table == 'tl_content')
        {
            $title = 'No Title: '.$db['id'];

            foreach ($titleFields as $field)
            {

                $ct = deserialize($db[$field]);

                // check if value is serialize
                if (is_array($ct) && !empty($ct)) {
                    $meta = Helper::parseStrForMeta($db[$field]);
                    $db[$field] = $meta;
                }

                if( $db[$field] && $db[$field] != '' && $field != 'type')
                {
                    return $db[$field].' ('.$db['type'].')';
                }
            }

            return $title;

        }

        return '';
    }

    /**
     * @param $table
     * @param $db
     * @param $dataArr
     * @param $dca
     * @return string
     */
    public function setCustomIcon($table, $db, $dataArr, $dca)
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

        if($table == 'tl_style_sheet' || $table == 'tl_style')
        {
            $iconName = 'css.gif';
        }

        if($table == 'tl_image_size')
        {
            $iconName = 'sizes.gif';
        }

        if($table == 'tl_newsletter')
        {
            $iconName = 'newsletter.gif';
        }

        if($table == 'tl_newsletter_recipients')
        {
            $iconName = 'member.gif';
        }

        if($table == 'tl_files' && $db['type'] == 'file')
        {

            $iconName = 'files.gif';

            if($db['extension'] == 'pdf')
            {
                $iconName = 'iconPDF.gif';
            }

            if($db['extension'] == 'jpg' || $db['extension'] == 'png' || $db['extension'] == 'tif' || $db['extension'] == 'bmp' || $db['extension'] == 'svg')
            {
                $iconName = 'iconJPG.gif';
            }

            if($db['extension'] == 'gif')
            {
                $iconName = 'iconGIF.gif';
            }

            if($db['extension'] == 'zip' || $db['extension'] == 'rar' )
            {
                $iconName = 'iconRAR.gif';
            }

            if($db['extension'] == 'css' )
            {
                $iconName = 'iconCSS.gif';
            }

            if($db['extension'] == 'js' )
            {
                $iconName = 'iconJS.gif';
            }

            if($db['extension'] == 'php' )
            {
                $iconName = 'iconPHP.gif';
            }

        }

        if($table == 'tl_files' && $db['type'] == 'folder')
        {
            $iconName = 'folderC.gif';
        }

        if($table == 'tl_page')
        {
            $iconName = 'regular.gif';

            if( $db['type'] == 'root')
            {
                $iconName = 'pagemounts.gif';
            }

            if( $db['type'] == 'forward')
            {
                $iconName = 'forward.gif';
            }

            if( $db['type'] == 'redirect')
            {
                $iconName = 'redirect.gif';
            }

            if( $db['type'] == 'error_403')
            {
                $iconName = 'error_403.gif';
            }

            if( $db['type'] == 'error_404')
            {
                $iconName = 'error_404.gif';
            }

            if( $db['type'] == 'error_404')
            {
                $iconName = 'error_404.gif';
            }

            if( $db['type'] == 'regular' && $db['hide'] == '1' )
            {
                $iconName = 'regular_2.gif';
            }

        }

        return $iconName;

    }

    /**
     * @param $table
     * @param $db
     * @param $dataArr
     * @param $dca
     * @return string
     */
    public function setCustomShortcut($table, $db, $dataArr, $dca)
    {
        $shortcut = '';

        if($table == 'tl_module')
        {
            $shortcut = 'fe';
        }

        if($table == 'tl_layout')
        {
            $shortcut = 'la';
        }

        if($table == 'tl_style_sheet' || $table == 'tl_style')
        {
            $shortcut = 'css';
        }

        if($table == 'tl_image_size')
        {
            $shortcut = 'si';
        }

        if($table == 'tl_newsletter')
        {
            $shortcut = 'nl';
        }

        if($table == 'tl_newsletter_recipients')
        {
            $shortcut = 'abo';
        }

        if($table == 'tl_files')
        {
            $shortcut = 'fi';
        }

        if($table == 'tl_files' && $db['extension'] == 'pdf')
        {
            $shortcut = 'pdf';
        }

        if( $table == 'tl_files' && ( $db['extension'] == 'png' || $db['extension'] == 'jpg' || $db['extension'] == 'gif' || $db['extension'] == 'svg' || $db['extension'] == 'tif' ) )
        {
            $shortcut = 'img';
        }

        if( $table == 'tl_files' && ( $db['extension'] == 'zip' || $db['extension'] == 'rar' ) )
        {
            $shortcut = 'zip';
        }

        return $shortcut;
    }

}