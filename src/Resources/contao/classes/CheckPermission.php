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

use Contao\BackendUser;

class CheckPermission
{

    /**
     * @param $do
     * @param $permArr
     * @param $searchItem
     * @return bool
     */
    public function checkPermission($do, $permArr, $searchItem)
    {
        switch ($do)
        {
            case 'page':
                return static::checkPage($permArr, $searchItem);
            break;
        }

        return true;
    }

    /**
     * @param $do
     * @param $arrRow
     * @param $permArr
     * @return bool|void
     */
    public function checkFieldPermission($do, $arrRow, $permArr)
    {
        switch ($do)
        {
            case 'page':
                return static::checkPageFields($permArr, $arrRow);
            break;
        }

        return true;
    }

    /**
     * @param $permArr
     * @param $searchItem
     */
    public function checkPageFields($permArr, $arrRow)
    {
        $return = false;
        return $return;
    }


    /**
     * @param $permArr
     * @param $searchItem
     * @return bool
     */
    public function checkPage($permArr, $searchItem)
    {
        $return = false;

        if( in_array( $searchItem['pid'], $permArr['pagemounts'] ) && in_array( $searchItem['type'], $permArr['allowedPageTypes'] ) )
        {

            $return = true;
        }

        if( in_array( $searchItem['docId'], $permArr['pagemounts'] ) && $searchItem['type'] == 'root' && in_array( $searchItem['type'], $permArr['allowedPageTypes'] ) )
        {
            $return = true;
        }

        return $return;
    }
}