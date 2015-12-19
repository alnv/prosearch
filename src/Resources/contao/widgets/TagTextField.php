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

use Contao\Database;
use Contao\Environment;
use Contao\Input;
use Contao\Widget;

class TagTextField extends Widget
{

    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';


    public function validator($varInput)
    {
        return parent::validator($varInput);
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string
     */
    public function generate()
    {

        $action = Input::get('actionPSTag');
        $tags = Input::get('ps_tags');
        $url = Environment::get('request');
		
		// request token fix
		// eventuell auf zentrale schnittstelle umleiten
		// alle ajax anfragen zentalisieren
		
        if($action == 'updateTags')
        {
            $this->updateTags($tags);
        }

        if($action == 'removeTags')
        {
            $this->removeTags($tags);
        }

        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/vendor/mootagify.js|static';
        $GLOBALS['TL_CSS'][] = 'system/modules/prosearch/assets/css/mootagify-bootstrap.css|static';
        $GLOBALS['TL_CSS'][] = 'system/modules/prosearch/assets/css/mootagify.css|static';

        $options = $this->options ? $this->options : array();

        $script = sprintf(
            '<script>'
            .'window.addEvent("domready", function(){

                var tagify = new mooTagify(document.id("tagWrap_%s"), null ,{
                    autoSuggest: true,
                    availableOptions: '.json_encode($options).'
                });

                tagify.addEvent("tagsUpdate", function(){

                    var tags = tagify.getTags();
                    document.id("ctrl_%s").set("value", tags.join());

                    new Request({url: "%s&actionPSTag=updateTags"}).get({"ps_tags": tags, "rt": Contao.request_token });

                });

                tagify.addEvent("tagRemove", function(tag){

                    var tags = tagify.getTags()

                    var deleted = tag;

                    document.id("ctrl_%s").set("value", tags.join());

                    new Request({url: "%s&actionPSTag=removeTags"}).get({ "ps_tags": deleted, "rt": Contao.request_token });

                });
            });'.'</script>', $this->strId, $this->strId, $url, $this->strId, $url);

        return sprintf('<input type="hidden" id="ctrl_%s" name="%s" value="%s"><div id="tagWrap_%s" class="hide"> <div class="tag-wrapper"></div> <div class="tag-input"> <input type="text" id="listTags" class="tl_text" name="listTags" value="%s" placeholder="+Tag"> </div> <div class="clear"></div></div>'.$script.'',
            $this->strId,
            $this->strName,
            specialchars($this->varValue),
            $this->strId,
            specialchars($this->varValue)
        );

    }


    public function updateTags($tags)
    {

        if(!is_array($tags))
        {
            $this->sendRes();
        }

        $valueArr = $tags ? $tags : array();
        $existedTags = array();

        $db = Database::getInstance();

        $tagsDB = $db->prepare('SELECT * FROM tl_prosearch_tags')->execute();

        while($tagsDB->next())
        {
            $existedTags[] = $tagsDB->tagname;
        }

        foreach($valueArr as $tagname)
        {
            if(!in_array($tagname, $existedTags))
            {
                $db->prepare('INSERT INTO tl_prosearch_tags (tstamp,tagname) VALUES (?,?)')->execute(time(), $tagname);
            }
        }

        $this->sendRes();
    }


    public function removeTags($tag)
    {
        if(!is_string($tag))
        {
            $this->sendRes();
        }

        $tagname = $tag ? $tag : '';
        $db = Database::getInstance();

        $existInSearchDB = $db->prepare("SELECT * FROM tl_prosearch_data WHERE tags LIKE ? ORDER BY tstamp DESC LIMIT 10")->execute("%$tagname%");

        if($existInSearchDB->count() > 1)
        {
            $this->sendRes();
        }

        $db->prepare('DELETE FROM tl_prosearch_tags WHERE tagname = ?')->execute($tagname);

        $this->sendRes();
    }


    public function sendRes()
    {
        header('Content-type: application/json');
        echo json_encode(array('state' => '200'));
        exit;
    }

}