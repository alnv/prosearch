<?php

namespace Alnv\ProSearchBundle\Classes;

use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

class Helper
{

    static public function getBEMode(): bool
    {
        return System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''));
    }

    static public function parseStrForMeta($serializeStr)
    {

        $arr = StringUtil::deserialize($serializeStr, true);
        $filterContent = ['h1','h2','h3','h4','h5','h6','h7'];
        $strContent = '';

        if(\is_array($arr))
        {
            foreach($arr as $val)
            {
                if(\is_array($val))
                {
                    $val = self::parseStrForMeta(StringUtil::deserialize($val));
                }

                if(\in_array($val, $filterContent))
                {
                    continue;
                }

                $strContent .= $val.' ';

            }
        }

        return trim($strContent);

    }

    static public function getDoParam($ctable): string
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

    static public function pluckModules($modules): array
    {
        $return = array();
        foreach($modules as $key => $coreModule)
        {
            $return[] = $key;
        }
        return $return;
    }

    static public function removeRequestTokenFromUri($reqStr): string
    {
        $requestUriArr = \explode('&', $reqStr);
        $temp = [];

        foreach($requestUriArr as $part)
        {
            if(\substr($part, 0, 2) == 'rt')
            {
                continue;
            }
            $temp[] = $part;
        }

        return \implode('&', $temp);
    }
}