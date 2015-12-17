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
class ProSearchPalette extends ProSearch
{

    public function insertProSearchLegend($strName)
    {

        $coreModulesArr = Helper::pluckModules($this->coreModules);

        if (in_array($strName, $coreModulesArr) && $GLOBALS['TL_DCA'][$strName]) {

            $palletesArr = $GLOBALS['TL_DCA'][$strName]['palettes'] ? $GLOBALS['TL_DCA'][$strName]['palettes'] : array();

            static::loadLanguageFile('tl_prosearch_data');

            // legend label setzten
            $GLOBALS['TL_LANG'][$strName]['prosearch_legend'] = $GLOBALS['TL_LANG']['tl_prosearch_data']['prosearch_legend'];

            foreach ($palletesArr as $k => $pallete) {

                if ($k == '__selector__') {

                    continue;

                }

                $palleteArr = explode(';', $pallete);

                if (count($palleteArr) == 1) {

                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] .= static::palettesStr();

                } else {

                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] = str_replace($palleteArr[0], $palleteArr[0].';'.static::palettesStr(), $GLOBALS['TL_DCA'][$strName]['palettes'][$k]);

                }

            }
            $GLOBALS['TL_DCA'][$strName]['config']['onload_callback'][]  = array('ProSearchPalette', 'getAvailabletags');
            $GLOBALS['TL_DCA'][$strName]['fields']['ps_title'] = static::ps_title();
            $GLOBALS['TL_DCA'][$strName]['fields']['ps_search_content'] = static::ps_search_content();
            $GLOBALS['TL_DCA'][$strName]['fields']['ps_tags'] = static::ps_tags();
            $GLOBALS['TL_DCA'][$strName]['fields']['ps_block_item'] = static::ps_block_item();
            $GLOBALS['TL_DCA'][$strName]['fields']['ps_block_usergroup'] = static::ps_block_usergroup();

        }
    }

    public function palettesStr()
    {
        return '{prosearch_legend:hide},ps_title,ps_search_content,ps_tags,ps_block_item,ps_block_usergroup';
    }

    public function ps_block_item()
    {
        return array(
            'exclude' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_block_item'],
            'inputType' => 'checkbox',
            'sql' => "char(1) NOT NULL default ''"
        );
    }

    public function ps_title()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_title'],
            'inputType' => 'text',
            'exclude' => true,
            'eval' => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default ''"
        );
    }

    public function ps_tags()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_tags'],
            'inputType' => 'tagTextField',
            'exclude' => true,
            //'save_callback' => array( array('ProSearchPalette', 'updateTagTable') ),
            'eval' => array(),
            'sql' => "text NULL"
        );
    }

    public function ps_search_content()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_search_content'],
            'inputType' => 'textarea',
            'exclude' => true,
            'eval' => array('decodeEntities' => true, 'tl_class' => 'clr'),
            'sql' => "text NULL"
        );
    }

    public function ps_block_usergroup()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_block_usergroup'],
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('multiple' => true),
            'options' => array(),
            'sql' => "blob NULL",
        );
    }



    public function getAvailabletags($dc)
    {

        $table = $dc->table;
        $options = array();

        //DB
        $tagsDB = $this->Database->prepare('SELECT * FROM tl_prosearch_tags ORDER BY tstamp DESC')->execute();

        while($tagsDB->next())
        {
            $options[] = $tagsDB->tagname;
        }
        
        //set
        $GLOBALS['TL_DCA'][$table]['fields']['ps_tags']['eval']['options'] = $options;
    }

    public function createColsIfNotExist()
    {
        //
    }

}