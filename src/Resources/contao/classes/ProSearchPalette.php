<?php namespace ProSearch;


/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   ProSearch
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2015 Alexander Naumov
 */
 
use Contao\Input;
 
class ProSearchPalette extends ProSearch
{

    private function getFields()
    {
        return array(
            'ps_title' => 'ps_title',
            'ps_search_content' => 'ps_search_content',
            'ps_tags' => 'ps_tags',
            'ps_block_item' => 'ps_block_item',
            'ps_block_usergroup' => 'ps_block_usergroup'
        );
    }

    /**
     * @param $strName
     */
    public function insertProSearchLegend($strName)
    {
		
		
        $coreModulesArr = Helper::pluckModules($this->coreModules);

        if (in_array($strName, $coreModulesArr) && $GLOBALS['TL_DCA'][$strName]) {


            if (!$this->createColsIfNotExist($strName)) {
                return null;
            }

            $palletesArr = $GLOBALS['TL_DCA'][$strName]['palettes'] ? $GLOBALS['TL_DCA'][$strName]['palettes'] : array();

            static::loadLanguageFile('tl_prosearch_data');

            // legend label setzen
            $GLOBALS['TL_LANG'][$strName]['prosearch_legend'] = $GLOBALS['TL_LANG']['tl_prosearch_data']['prosearch_legend'];

            foreach ($palletesArr as $k => $pallete) {

                if ($k == '__selector__') {
                    continue;
                }

                $palleteArr = explode(';', $pallete);

                if (count($palleteArr) == 1) {
                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] .= ';'.static::palettesStr();
                } else {
                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] = str_replace($palleteArr[0], $palleteArr[0] . ';' . static::palettesStr(), $GLOBALS['TL_DCA'][$strName]['palettes'][$k]);
                }

            }

            $GLOBALS['TL_DCA'][$strName]['config']['onload_callback'][] = array('ProSearchPalette', 'getAvailabletags');

            foreach ($this->getFields() as $field => $method) {
                $GLOBALS['TL_DCA'][$strName]['fields'][$field] = call_user_func(array('ProSearchPalette', $method));
            }

        }
    }

    /**
     * @return string
     */
    public function palettesStr()
    {
        return '{prosearch_legend:hide},ps_title,ps_search_content,ps_tags,ps_block_item,ps_block_usergroup';
    }

    /**
     * @return array
     */
    public function ps_block_item()
    {
        return array(
            'exclude' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_block_item'],
            'inputType' => 'checkbox',
            'sql' => "char(1) NOT NULL default ''"
        );
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function ps_tags()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_tags'],
            'inputType' => 'tagTextField',
            'exclude' => true,
            'eval' => array(),
            'sql' => "varchar(1024) NOT NULL default ''"
        );
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function ps_block_usergroup()
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_block_usergroup'],
            'inputType' => 'checkboxWizard',
            'foreignKey' => 'tl_user_group.name',
            'exclude' => true,
            'eval' => array('multiple' => true),
            'sql' => "blob NULL",
            'relation' => array('type' => 'belongsToMany', 'load' => 'lazy')
        );
    }


    /**
     * @param $dc
     */
    public function getAvailabletags($dc)
    {

        $table = $dc->table;
        $options = array();

        //DB
        $tagsDB = $this->Database->prepare('SELECT * FROM tl_prosearch_tags ORDER BY tstamp DESC')->execute();

        while ($tagsDB->next()) {
            $options[] = $tagsDB->tagname;
        }

        //set
        $GLOBALS['TL_DCA'][$table]['fields']['ps_tags']['eval']['options'] = $options;

    }


    /**
     * @param $strName
     * @return bool
     */
    public function createColsIfNotExist($strName)
    {
        foreach ($this->getFields() as $k => $v) {
            if (!$this->Database->fieldExists($k, $strName)) {
                $field = call_user_func(array('ProSearchPalette', $v));
                $this->createCol($strName, $k, $field);
            }
        }

        return true;

    }

    /**
     * @param $strName
     * @param $col
     * @param array $field
     */
    private function createCol($strName, $col, $field = array())
    {
        $this->Database->prepare('ALTER TABLE ' . $strName . ' ADD ' . $col . ' ' . $field['sql'] . '')->execute();
    }

}