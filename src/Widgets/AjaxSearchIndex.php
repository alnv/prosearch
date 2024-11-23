<?php

namespace Alnv\ProSearchBundle\Widgets;

use Contao\Config;
use Contao\StringUtil;
use Contao\Widget;


class AjaxSearchIndex extends Widget
{

    protected $strTemplate = 'be_widget';

    public function generate(): string
    {

        $bStr = $GLOBALS['TL_LANG']['MSC']['ajaxSearchIndex']['button'] ?? '';

        $activeModules = StringUtil::deserialize(Config::get('searchIndexModules'), true);

        $json = \json_encode($activeModules);

        $GLOBALS['TL_MOOTOOLS'][] = '<script>var proSearchActiveModules = '.$json.';</script>';
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/alnvprosearch/JsIndex.js|static';

        return  '<div class="index_list"><ul class="ul"></ul></div><div class="ajaxSearchIndex"><a class="tl_submit" style="margin-bottom: 5px; margin-top: 5px" onclick="Backend.getScrollOffset();return AjaxRequest.ajaxSearchIndex()">'.$bStr.'</a></div>';

    }

}
