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

use Contao\Config;
use Contao\Widget;

/**
 * Class AjaxSearchIndex
 */
class AjaxSearchIndex extends Widget
{

    /**
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * @return string
     */
    public function generate()
    {
        // translation
        $bStr = $GLOBALS['TL_LANG']['MSC']['ajaxSearchIndex']['button'];

        // load active modules
        $activeModules = deserialize(Config::get('searchIndexModules'));

        // encode to json
        $json = json_encode($activeModules);

        // set to global js varaible
        $GLOBALS['TL_MOOTOOLS'][] = '<script>var proSearchActiveModules = '.$json.';</script>';

        // load js
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/prosearch/assets/JsIndex.js|static';

        return  '<div class="index_list"><ul class="ul"></ul></div><div class="ajaxSearchIndex"><a class="tl_submit" style="margin-bottom: 5px; margin-top: 5px" onclick="Backend.getScrollOffset();return AjaxRequest.ajaxSearchIndex()">'.$bStr.'</a></div>';

    }

}
