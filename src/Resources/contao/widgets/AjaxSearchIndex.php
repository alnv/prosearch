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
        $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['PS_PUBLIC_PATH'].'JsIndex.js|static';

        return  '<div class="index_list"><ul class="ul"></ul></div><div class="ajaxSearchIndex"><a class="tl_submit" style="margin-bottom: 5px; margin-top: 5px" onclick="Backend.getScrollOffset();return AjaxRequest.ajaxSearchIndex()">'.$bStr.'</a></div>';

    }

}
