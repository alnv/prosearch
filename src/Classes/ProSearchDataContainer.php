<?php

namespace Alnv\ProSearchBundle\Classes;

use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;
use Contao\Input;
use Contao\Environment;

class ProSearchDataContainer extends DataContainer
{

    public function getPalette(): string
    {
        return '';
    }

    public function save($varValue)
    {
        //
    }

    public function createButtons($arrRow): string
    {

        $strTable = $arrRow['dca'];
        $this->loadDataContainer($strTable);

        if (empty($GLOBALS['TL_DCA'][$strTable]['list']['operations'])) {
            return '';
        }

        $return = '';

        $operations = $GLOBALS['TL_DCA'][$strTable]['list']['operations'];
        $mode = $GLOBALS['TL_DCA'][$strTable]['list']['sorting']['mode'];

        $id = StringUtil::specialchars(rawurldecode($arrRow['docId']));
        $id = $id ? '&amp;id=' . $id : '';
        $fmTable = substr($arrRow['dca'], 0, 2);
        //$pid = $arrRow['pid'] ? '&amp;pid='.$arrRow['pid'] : '';
        $table = $arrRow['dca'] ? '&amp;table=' . $arrRow['dca'] : '';

        if (($operations['editheader'] ?? '') || ($operations['edit'] ?? '')) {
            $href = 'act=edit';
            $queryStr = $href . $id . $table;

            $tagsStr = '';

            if ($arrRow['dca'] != 'tl_files') {
                $tagsStr = '[' . $arrRow['docId'] . ']';
            }

            if ($arrRow['tags']) {
                $tags = \explode(',', $arrRow['tags']);

                foreach ($tags as $tag) {
                    $tagsStr .= '<span class="ps_tag">' . $tag . '</span>';
                }
            }

            $title = strlen($arrRow['title']) > 100 ? substr($arrRow['title'], 0, 100) . '…' : $arrRow['title'];
            $arrRow['dynTable'] = null;

            $return .= '<div class="title"><span class="icon" title="' . strtoupper($arrRow['shortcut']) . '">' . $arrRow['icon'] . '</span><a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '&popup=1" class="search-result" tabindex="1" onclick="Backend.openModalIframe({\'width\':960,\'title\':\'' . $arrRow['title'] . '\',\'url\':this.href});return false"><span>' . mb_convert_encoding($title, 'UTF-8') . '</span> <span class="info">' . $tagsStr . '</span></a></div>';
        }

        $return .= '<div class="operations">';

        $ctableArr = StringUtil::deserialize($arrRow['ctable'], true);
        $mode = $mode ?: 5;

        if (is_array($ctableArr) && !empty($ctableArr) && $mode != 5 && $fmTable != 'fm') {

            $href = '';
            $icon = '';
            $ptable = '';
            $arrRow['dynTable'] = null;

            foreach ($ctableArr as $ctable) {
                $href = '&amp;table=' . $ctable . '';
                $icon = 'edit.gif';
                $ptable = '';

            }

            $queryStr = $href . $id . $ptable;
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';

        }

        // go to ietm
        if (($operations['editheader'] ?? '') || ($operations['edit'] ?? '')) {
            $href = 'act=edit';
            $icon = 'header.gif';
            $ptable = $table;
            $arrRow['dynTable'] = null; // reset dyntable if not needed
            $queryStr = $href . $id . $ptable;
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';
        }

        if ($fmTable == 'fm') {
            // list view
            $href = '&amp;table=tl_content';
            $fmType = '&view=list';
            $icon = $GLOBALS['PS_PUBLIC_PATH'] . 'images/page.png';
            $queryStr = $href . $fmType . $id;
            $arrRow['dynTable'] = null;
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';

            //detailview
            $icon = $GLOBALS['PS_PUBLIC_PATH'] . 'images/detail.png';
            $fmType = '&view=detail';
            $queryStr = $href . $fmType . $id;
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';

        }

        if ($arrRow['doTable'] == 'page') {

            $pageID = $arrRow['docId'];

            // page node
            $pageNodeQueryStr = 'node=' . $pageID;
            $dca['dynTable'] = null; // reset dyntable if not needed
            $icon = $arrRow['icon'];
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $pageNodeQueryStr) . '" tabindex="1">' . $icon . '</a>';

            // article node
            $articleNodeQueryStr = 'node=' . $pageID;
            $arrRow['dynTable'] = 'article';
            $icon = 'article.gif';
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $articleNodeQueryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';

            // Open Frontend Page
            if ($arrRow['type'] == 'regular') {
                $feQueryStr = 'page=' . $pageID;
                $arrRow['dynTable'] = 'feRedirect';
                $icon = 'redirect_2.gif';
                $return .= '<a href="' . $this->addToSearchUrl($arrRow, $feQueryStr) . '" target="_blank" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';
            }

        }

        if ($arrRow['dca'] == 'tl_files' && in_array($arrRow['extension'], $GLOBALS['PS_EDITABLE_FILES'])) {
            $href = 'act=source';
            $queryStr = $href . $id;
            $icon = 'editor.gif';
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1">' . Image::getHtml($icon, $arrRow['title']) . '</a>';
        }

        // show
        if ($operations['show'] && $arrRow['dca'] != 'tl_files') {
            $href = 'act=show';
            $icon = 'show.gif';
            $arrRow['dynTable'] = null;
            $attributes = (($operations['show']['attributes'] ?? '') != '') ? ' ' . ltrim(sprintf(($operations['show']['attributes'] ?? ''), $id, $id)) : '';
            $queryStr = $href . $id . $table . '&amp;popup=1';
            $return .= '<a href="' . $this->addToSearchUrl($arrRow, $queryStr) . '" tabindex="1" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", sprintf($arrRow['title'], $arrRow['docId']))) . '\',\'url\':this.href});return false"' . $attributes . '>' . Image::getHtml($icon) . '</a> ';
        }

        if ($operations['show'] && $arrRow['dca'] == 'tl_files') {
            $href = 'contao/popup.php?src=' . base64_encode($arrRow['docId']);
            $icon = 'show.gif';
            $arrRow['dynTable'] = null;
            $return .= '<a href="' . $href . '" tabindex="1" onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . str_replace("'", "\\'", StringUtil::specialchars($arrRow['title'], false, true)) . '\',\'url\':this.href,\'height\':500});return false" >' . Image::getHtml($icon) . '</a>';
        }

        $return .= '</div>';
        return trim($return);
    }

    public function addToSearchUrl($dca, $queryStr, $blnAddRef = true, $arrUnset = array())
    {

        $strRequest = $queryStr;

        $strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);

        if ($strRequest != '' && $blnAddRef) {
            $strRequest .= '&amp;ref=' . Input::get('ref') ?: '';
        }

        $queries = preg_split('/&(amp;)?/i', Environment::get('queryString'));

        // Overwrite existing parameters
        foreach ($queries as $k => $v) {
            list($key) = explode('=', $v);

            if ($key == 'do') {
                $queries[$k] = $dca['dynTable'] ? 'do=' . $dca['dynTable'] . '' : 'do=' . $dca['doTable'] . '';
            }

            if ($key == 'index') {
                unset($queries[$k]);
            }

            if ($key == 'ajaxRequestForProSearch') {
                unset($queries[$k]);
            }

            if ($key == 'searchQuery') {
                unset($queries[$k]);
            }

            if (in_array($key, $arrUnset) || preg_match('/(^|&(amp;)?)' . preg_quote($key, '/') . '=/i', $strRequest)) {
                unset($queries[$k]);
            }
        }

        $href = '?';

        if (!empty($queries)) {
            $href .= implode('&amp;', $queries) . '&amp;';
        }

        // $route = System::getContainer()->get('request_stack')->getCurrentRequest()->get('_route');

        return $href . str_replace(' ', '%20', $strRequest);
    }
}