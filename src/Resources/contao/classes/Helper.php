<?php

namespace ProSearch;

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
     * @param $serializeStr
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

        return trim($strContent);

    }

    /**
     * @param $ctable
     * @return int|string
     */
    static public function getDoParam($ctable)
    {

        if ( $ctable && $GLOBALS['PS_SEARCHABLE_MODULES'] && is_array($GLOBALS['PS_SEARCHABLE_MODULES'])) {

            foreach ($GLOBALS['PS_SEARCHABLE_MODULES'] as $do => $module) {

                foreach ($module['tables'] as $table) {

                    if ($table == $ctable) {

                        return $do;

                    }

                }

            }

        }
        return '';
    }

    /**
     * @param $modules
     * @return array
     */
    static public function pluckModules($modules)
    {
        $return = array();
        foreach($modules as $key => $coreModule)
        {
            $return[] = $key;
        }
        return $return;
    }

    /**
     * @param $reqStr
     * @return string
     */
    static public function removeRequestTokenFromUri($reqStr)
    {
        $requestUriArr = explode('&', $reqStr);
        $temp = [];

        foreach($requestUriArr as $part)
        {
            if(substr($part, 0, 2) == 'rt')
            {
                continue;
            }
            $temp[] = $part;
        }

        return implode('&', $temp);

    }

}