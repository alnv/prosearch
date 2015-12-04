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

use Contao\DataContainer;
use Contao\Image;

class ProSearchDataContainer extends DataContainer
{
    public function getPalette()
    {
        return '';
    }

    public function save($varValue)
    {
        //
    }

    public function createButtons($arrRow, $dca)
    {
        $strTable = $dca['dca'];

        if (empty($GLOBALS['TL_DCA'][$strTable]['list']['operations']))
        {
            return '';
        }

        $return = '';

        $operations = $GLOBALS['TL_DCA'][$strTable]['list']['operations'];


        $id = specialchars(rawurldecode($dca['docId']));
        $id = $id ? '&amp;id='.$id : '';
        $pid = $dca['pid'] ? '&amp;pid='.$dca['pid'] : '';
        $table = $dca['dca'] ? '&amp;table='.$dca['dca'] : '';

        if( $operations['editheader'] || $operations['edit'] )
        {
            $href = 'act=edit';
            $queryStr = $href.$id.$table;
            $return .= '<div class="title"><span class="icon">'.$dca['icon'].'</span><a href="'.$this->addToSearchUrl($dca, $queryStr).'" onclick="Backend.openModalIframe({\'width\':900,\'title\':\''.$dca['title'].'\',\'url\':this.href});return false">'.$dca['title'].' <span class="info">['.$dca['docId'].']</span></a></div>';
        }

        $return .= '<div class="operations">';

        // go to ietm
        if( $operations['editheader'] || $operations['edit'] )
        {

            // if has childs go to overview
            $ctableArr = deserialize($dca['ctable']);
            $href = 'act=edit';
            $icon = 'edit.gif';

            if(is_array($ctableArr))
            {
                foreach($ctableArr as $ctable)
                {
                    $href = '&amp;table='.$ctable.'';
                    $icon = 'header.gif';
                    $table = '';
                }
            }

            $queryStr = $href.$id.$table;
            $return .= '<a href="'.$this->addToSearchUrl($dca, $queryStr).'">'.Image::getHtml($icon,$dca['title']).'</a>';
        }

        //copy
        if( $operations['copy'] )
        {
            $href = 'act=copy';
            $attributes = ($operations['copy']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['copy']['attributes'], $id, $id)) : '';
            $queryStr = $href.$id.$table.$pid;
            $icon = 'copy.gif';
            $return .= '<a href="'.$this->addToSearchUrl($dca, $queryStr).'" '.$attributes.'>'.Image::getHtml($icon,$dca['title']).'</a>';
        }

        // delete item
        if( $operations['delete'] )
        {
            $href = 'act=delete';
            $attributes = ($operations['delete']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['delete']['attributes'], $id, $id)) : '';
            $queryStr = $href.$id.$table.$pid;
            $icon = 'delete.gif';
            $return .= '<a href="'.$this->addToSearchUrl($dca, $queryStr).'" '.$attributes.'>'.Image::getHtml($icon,$dca['title']).'</a>';
        }

        // show
        if( $operations['show'] && $dca['dca'] != 'tl_files' )
        {
            $href = 'act=show';
            $icon = 'show.gif';
            $attributes = ($operations['show']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['show']['attributes'], $id, $id)) : '';
            $queryStr = $href.$id.$table.'&amp;popup=1';
            $return .= '<a href="'.$this->addToSearchUrl($dca, $queryStr).'" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG'][$strTable]['show'][1], $arrRow['id']))).'\',\'url\':this.href});return false"'.$attributes.'>'.Image::getHtml($icon).'</a> ';

        }

        if( $operations['show'] && $dca['dca'] == 'tl_files' )
        {
            $href = 'contao/popup.php?src='.base64_encode($arrRow['path']).'';
            $icon = 'show.gif';
            //$attributes = ($operations['show']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['show']['attributes'], $id, $id)) : '';
            $return .= '<a href="'.$href.'" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.str_replace("'", "\\'", specialchars($arrRow['name'], false, true)).'\',\'url\':this.href,\'height\':500});return false" >'.Image::getHtml($icon).'</a>';
        }

        $return .= '</div>';


        return trim($return);
    }



    public function addToSearchUrl($dca, $queryStr, $blnAddRef=true, $arrUnset=array())
    {

        $strRequest = $queryStr;

        $strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);

        if ($strRequest != '' && $blnAddRef)
        {
            $strRequest .= '&amp;ref=' . TL_REFERER_ID;
        }

        $queries = preg_split('/&(amp;)?/i', \Environment::get('queryString'));

        // Overwrite existing parameters
        foreach ($queries as $k=>$v)
        {
            list($key) = explode('=', $v);

            if($key == 'do')
            {
                $queries[$k] = 'do='.$dca['doTable'].'';
            }

            if($key == 'index')
            {
                unset($queries[$k]);
            }

            if (in_array($key, $arrUnset) || preg_match('/(^|&(amp;)?)' . preg_quote($key, '/') . '=/i', $strRequest))
            {
                unset($queries[$k]);
            }
        }

        $href = '?';

        if (!empty($queries))
        {
            $href .= implode('&amp;', $queries) . '&amp;';
        }

        return TL_SCRIPT . $href . str_replace(' ', '%20', $strRequest);


    }

}