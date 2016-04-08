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

/**
 * Class Helper
 * @package ProSearch
 */
class Helper
{

    /**
     * @var array
     */
	public static $validSums = array(
		
		'5113d4676ccf062850f9915d8142e5a7',
		'7d450eabea3b3b0c4f000848344c354a',
		'd05f85dbcb74ff4daab992e5226b777a',
		'32f973cb440240f075ea9f719e6d7a03',
		'347a7d8dae7b43af0df081ef4df0313b',
		'a22b40bdc33353e87e01c6b51cdb00d9',
		'f0ccfd3aa6787edfac82ce9b6704a496',
		'187a15d1f58e33bb6a2b79117c67cc42',
		'd15085005710bd16884daf3f586b75c7',
		'e8a5ca146d940bd8e590b0fe361c3df7',
		
		'bc89f0540e8f632218c325887fdebe9e',
		'016b97bfa8d19ac7788a292c6d78a571',
		'6554a914b14b4be1d381bd59013c9e95',
		'43787acee7e8f0570179ff86f6aa9017',
		'0c2424f9bd221b4692d0afce0192a717',
		'6304e03d0037b3673bd2a50ea3076ec7',
		'3ba9c986544998ce75ec3e8e0c0c4c84',
		'6bb6b4bdb890dd1ad98c20d1ba5a70e3',
		'56923038b0405e644b8064ea8324bd11',
		'b4ca86d80e709e132563eed207d2fd6a',
		
		'65066538631cb5b0251429c98a1284df',
		'2ab91fc2244d8831241ff604a0e21737',
		'cc09d512fef58791e5c1adc20343f209',
		'246f5417e9cefd451546564811c6f025',
		'a93f286e800c3014ec3de64610510cee',
		'084ba41715923817f7a2b32b3074e134',
		'd46e6a8d2f1281bd6431e1ed4210db20',
		'e79050a246cfacaa01f3a05402942f13',
		'58e87305e20966f4715702f3d90a3f92',
		'5b1d59fcecc0a2306407c8bf84cc03d7',
		
		'a48d551d252b4b34d4c2056d89625124',
		'fb400e4af2cc265a2f9600a0abef1b25',
		'ccf3b87cbfa2ab184f1dab6ebd859d90',
		'7912ea6c34afe42ceb8001844208fbdf',
		'd425bc9cdff982b5fa2605a8d18a8a81',
		'985bdbe749540bccaf2003a840555c64',
		'10c4fcc6b82572962ea2af7fbc454cab',
		'cf2ed1462688e8a7a156ebdc08269d66',
		'35c1100c6bf8e1b3f8146db918a2eb2d',
		'd282a32df63eb7e00bb78cf6930f631b',
		
		'fb5ca51ff5ebb185f9a4e9c133a71472',
		'd1a8480d46a0d3f1c2304969251201bf',
		'6a74a420d5799daff57cbe12758ae4e0',
		'627d31e9762d21f2e8504529a5a4217e',
		'09088be2f30329d9e9f3616d71a5b598',
		'6f230de852d68d68b9a00dcea4383cb4',
		'a49703ccd8e29649e921565a555543a8',
		'b96cad4d5e9918fd11c59079e3e38542',
		'ed72bf75151d073ef2ef1f9f77c2be8b',
		'0a9a41c58494a81d751c51572944bbe3',
		
	);
	
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