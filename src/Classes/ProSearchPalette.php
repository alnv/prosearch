<?php

namespace Alnv\ProSearchBundle\Classes;

use Contao\Config;
use Contao\Input;
use Contao\StringUtil;


class ProSearchPalette extends ProSearch
{

    private function getFields(): array
    {
        return array(
            'ps_title' => 'ps_title',
            'ps_search_content' => 'ps_search_content',
            'ps_tags' => 'ps_tags',
            'ps_block_item' => 'ps_block_item',
            'ps_block_usergroup' => 'ps_block_usergroup'
        );
    }

    public function insertProSearchLegend($strName)
    {

        $activeTables = StringUtil::deserialize(Config::get('searchIndexModules'));
        $coreModulesArr = $activeTables ?: array();

        if (in_array($strName, $coreModulesArr) && $GLOBALS['TL_DCA'][$strName]) {


            if (!$this->createColsIfNotExist($strName)) {
                return;
            }

            $palletesArr = $GLOBALS['TL_DCA'][$strName]['palettes'] ?? array();

            static::loadLanguageFile('tl_prosearch_data');

            // legend label setzen
            $GLOBALS['TL_LANG'][$strName]['prosearch_legend'] = $GLOBALS['TL_LANG']['tl_prosearch_data']['prosearch_legend'];

            foreach ($palletesArr as $k => $pallete) {

                if ($k == '__selector__') {
                    continue;
                }

                $palleteArr = explode(';', $pallete);

                if (count($palleteArr) == 1) {
                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] .= ';' . static::palettesStr();
                } else {
                    $GLOBALS['TL_DCA'][$strName]['palettes'][$k] = str_replace($palleteArr[0], $palleteArr[0] . ';' . static::palettesStr(), $GLOBALS['TL_DCA'][$strName]['palettes'][$k]);
                }

            }

            $GLOBALS['TL_DCA'][$strName]['config']['onload_callback'][] = array(ProSearchPalette::class, 'getAvailabletags');
            foreach ($this->getFields() as $field => $method) {
                $GLOBALS['TL_DCA'][$strName]['fields'][$field] = call_user_func(array(ProSearchPalette::class, $method));
            }
        }
    }

    public function palettesStr(): string
    {
        return '{prosearch_legend:hide},ps_title,ps_search_content,ps_tags,ps_block_item,ps_block_usergroup';
    }

    public function ps_block_item(): array
    {
        return array(
            'exclude' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_block_item'],
            'inputType' => 'checkbox',
            'sql' => "char(1) NOT NULL default ''"
        );
    }

    public function ps_title(): array
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_title'],
            'inputType' => 'text',
            'exclude' => true,
            'eval' => array('maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default ''"
        );
    }

    public function ps_tags(): array
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_tags'],
            'inputType' => 'tagTextField',
            'exclude' => true,
            'eval' => array(),
            'sql' => "varchar(1024) NOT NULL default ''"
        );
    }

    public function ps_search_content(): array
    {
        return array(
            'label' => &$GLOBALS['TL_LANG']['tl_prosearch_data']['ps_search_content'],
            'inputType' => 'textarea',
            'exclude' => true,
            'eval' => array('decodeEntities' => true, 'tl_class' => 'clr'),
            'sql' => "text NULL"
        );
    }

    public function ps_block_usergroup(): array
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

    public function getAvailabletags($dc = null)
    {

        if (!$dc) {
            return;
        }

        $options = array();
        $table = $dc->table;

        $tagsDB = $this->Database->prepare('SELECT * FROM tl_prosearch_tags ORDER BY tstamp DESC')->execute();

        while ($tagsDB->next()) {
            $options[] = $tagsDB->tagname;
        }

        $GLOBALS['TL_DCA'][$table]['fields']['ps_tags']['eval']['options'] = $options;
    }

    public function createColsIfNotExist($strName)
    {
        foreach ($this->getFields() as $k => $v) {

            if (!$this->Database->fieldExists($k, $strName) && Input::get('do') != 'repository_manager' && Input::get('do') != '') {
                $field = call_user_func(array(ProSearchPalette::class, $v));
                $this->createCol($strName, $k, $field);

            }
        }

        return true;
    }

    private function createCol($strName, $col, $field = array())
    {
        $this->Database->prepare('ALTER TABLE ' . $strName . ' ADD ' . $col . ' ' . $field['sql'] . '')->execute();
    }
}