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

/**
 * Class ProSearchDataContainer
 * @package ProSearch
 */
class ProSearchDataContainer extends DataContainer
{
    /**
     * @return string
     */
    public function getPalette()
    {
        return '';
    }

    /**
     * @param mixed $varValue
     */
    public function save($varValue)
    {
        //
    }

    /**
     * @param $arrRow
     * @param $admin
     * @param $permArr
     * @return string
     */
    public function createButtons($arrRow)
    {

        $strTable = $arrRow['dca'];
		$this->loadDataContainer($strTable);


        if (empty($GLOBALS['TL_DCA'][$strTable]['list']['operations']))
        {
            return '';
        }

        $return = '';

        $operations = $GLOBALS['TL_DCA'][$strTable]['list']['operations'];
		$mode = $GLOBALS['TL_DCA'][$strTable]['list']['sorting']['mode'];

        $id = specialchars(rawurldecode($arrRow['docId']));
        $id = $id ? '&amp;id='.$id : '';
        //$pid = $arrRow['pid'] ? '&amp;pid='.$arrRow['pid'] : '';
        $table = $arrRow['dca'] ? '&amp;table='.$arrRow['dca'] : '';

        if( $operations['editheader'] || $operations['edit'] )
        {
            $href = 'act=edit';
            $queryStr = $href.$id.$table;

            $info = strlen($arrRow['docId']) > 35 ? substr($arrRow['docId'],0,35).'…' : $arrRow['docId'];
            $info = '['.$info.']';
            if($arrRow['tags'])
            {
                $tags = explode(',', $arrRow['tags']);
                $info = '';
                foreach($tags as $tag)
                {
                    $info .= '<span class="ps_tag">'.$tag.'</span>';
                }
            }

            $title = strlen($arrRow['title']) > 75 ? substr($arrRow['title'],0,75).'…' : $arrRow['title'];
            $arrRow['dynTable'] = null; // reset dyntable if not needed
            $return .= '<div class="title"><span class="icon">'.$arrRow['icon'].'</span><a href="'.$this->addToSearchUrl($arrRow, $queryStr).'" class="search-result" tabindex="1" onclick="Backend.openModalIframe({\'width\':900,\'title\':\''.$arrRow['title'].'\',\'url\':this.href});return false"><span>'.$title.'</span> <span class="info">'.$info.'</span></a></div>';
        }

        $return .= '<div class="operations">';

        // go to ietm
        if( $operations['editheader'] || $operations['edit'] )
        {

            // if has childs go to overview
            $ctableArr = deserialize($arrRow['ctable']);
            $href = 'act=edit';
            $icon = 'header.gif';
			$mode = $mode ? $mode : 5;
			$ptable = $table;
            $arrRow['dynTable'] = null; // reset dyntable if not needed

            if(is_array($ctableArr)  && $mode != 5 )
            {
                foreach($ctableArr as $ctable)
                {
                    $href = '&amp;table='.$ctable.'';
                    $icon = 'edit.gif';
                    $ptable = '';
                }
            }

            $queryStr = $href.$id.$ptable;
            $return .= '<a href="'.$this->addToSearchUrl($arrRow, $queryStr).'" tabindex="1">'.Image::getHtml($icon,$arrRow['title']).'</a>';
        }

        if($arrRow['doTable'] == 'page')
        {

            $pageID = $arrRow['docId'];

            // page node
            $pageNodeQueryStr = 'node='.$pageID;
            $dca['dynTable'] = null; // reset dyntable if not needed
            $icon = $arrRow['icon'];
            $return .= '<a href="'.$this->addToSearchUrl($arrRow, $pageNodeQueryStr).'" tabindex="1">'.$icon.'</a>';

            // article node
            $articleNodeQueryStr = 'node='.$pageID;
            $arrRow['dynTable'] = 'article';
            $icon = 'article.gif';
            $return .= '<a href="'.$this->addToSearchUrl($arrRow, $articleNodeQueryStr).'" tabindex="1">'.Image::getHtml($icon, $arrRow['title']).'</a>';

            // Open Frontend Page
            if($arrRow['type'] == 'regular')
            {
                $feQueryStr = 'page='.$pageID;
                $arrRow['dynTable'] = 'feRedirect';
                $icon = 'preview.gif';
                $return .= '<a href="'.$this->addToSearchUrl($arrRow, $feQueryStr).'" target="_blank" tabindex="1">'.Image::getHtml($icon, $arrRow['title']).'</a>';
            }

        }

        if( $arrRow['dca'] == 'tl_files' && in_array($arrRow['extension'], $GLOBALS['PS_EDITABLE_FILES']))
        {
            $href = 'act=source';
            $queryStr = $href.$id;
            $icon = 'editor.gif';
            $return .= '<a href="'.$this->addToSearchUrl($arrRow, $queryStr).'" tabindex="1">'.Image::getHtml($icon, $arrRow['title']).'</a>';
        }

        // show
        if( $operations['show'] && $arrRow['dca'] != 'tl_files' )
        {
            $href = 'act=show';
            $icon = 'show.gif';
            $arrRow['dynTable'] = null; // reset dyntable if not needed
            $attributes = ($operations['show']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['show']['attributes'], $id, $id)) : '';
            $queryStr = $href.$id.$table.'&amp;popup=1';
            $return .= '<a href="'.$this->addToSearchUrl($arrRow, $queryStr).'" tabindex="1" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG'][$strTable]['show'][1], $arrRow['docId']))).'\',\'url\':this.href});return false"'.$attributes.'>'.Image::getHtml($icon).'</a> ';
        }

        if( $operations['show'] && $arrRow['dca'] == 'tl_files' )
        {
            $href = 'contao/popup.php?src='.base64_encode($arrRow['docId']).'';
            $icon = 'show.gif';
            $arrRow['dynTable'] = null; // reset dyntable if not needed
            //$attributes = ($operations['show']['attributes'] != '') ? ' ' . ltrim(sprintf($operations['show']['attributes'], $id, $id)) : '';
            $return .= '<a href="'.$href.'" tabindex="1" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.str_replace("'", "\\'", specialchars($arrRow['title'], false, true)).'\',\'url\':this.href,\'height\':500});return false" >'.Image::getHtml($icon).'</a>';
        }

        $return .= '</div>';
        return trim($return);
    }


    /**
     * @param $dca
     * @param $queryStr
     * @param bool|true $blnAddRef
     * @param array $arrUnset
     * @return string
     */
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
                $queries[$k] = $dca['dynTable'] ? 'do='.$dca['dynTable'].'' : 'do='.$dca['doTable'].'';
            }

            if($key == 'index')
            {
                unset($queries[$k]);
            }
            
            if($key == 'ajaxRequestForProSearch')
            {
                unset($queries[$k]);
            }
			
			if($key == 'searchQuery')
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