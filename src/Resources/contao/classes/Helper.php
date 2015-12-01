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

/**
 * Class Helper
 * @package ProSearch
 */
class Helper
{
    /**
     * @return string
     */
    static public function getBEMode()
    {
        return TL_MODE;
    }

    /**
     * @param $str
     * @return string
     */
    static public function parseStrForMeta($serializeStr)
    {
        $arr = deserialize($serializeStr);
        $filterContent = array('h1','h2','h3','h4','h5','h6','h7');
        $strContent = '';

        if(is_array($arr))
        {
            foreach($arr as $val)
            {
                if(is_array($val))
                {
                    $val = self::parseStrForMeta(deserialize($val));
                }

                if(in_array($val, $filterContent))
                {
                    continue;
                }

                $strContent .= $val.' ';

            }
        }

        return $strContent;

    }

}