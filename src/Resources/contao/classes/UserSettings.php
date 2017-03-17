<?php

namespace ProSearch;

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

class UserSettings {


    private $blnInitialized = false;
    private $strPlaceholder = '<!-- ### %PROSEARCH_SCRIPT_TAG% ### -->';


    public function initializeSettings( $strContent, $strTemplate ) {

        if ( $strTemplate == 'be_main' && !$this->blnInitialized ) {

            $objUser = \BackendUser::getInstance();

            $arrSettings = [

                'enable' => true,
                'id' => $objUser->id,
                'shortcut' => $objUser->keyboard_shortcut ? $objUser->keyboard_shortcut : 'alt+m',
            ];

            if ( isset( $objUser->modules ) && !empty( $objUser->modules ) && is_array( $objUser->modules ) ) {

                $arrSettings['enable'] = in_array( 'prosearch_settings' , $objUser->modules );
            }

            $strContent = str_replace( $this->strPlaceholder, '<script>var UserSettings = ' . json_encode( $arrSettings ) . ';</script>',  $strContent );
            $this->blnInitialized = true;
        }

        return $strContent;
    }
}
