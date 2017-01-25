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

    public function setUserSettingsOnLogin( $objUser ) {

        if ( $objUser instanceof \BackendUser ) {

            $arrSettings = array(

                'id' => $objUser->id,
                'enable' => true,
                'shortcut' => $objUser->keyboard_shortcut ? $objUser->keyboard_shortcut : 'alt+m',
            );

            if ( isset( $objUser->modules ) && !empty( $objUser->modules ) && is_array( $objUser->modules ) ) {

                $arrSettings['enable'] = in_array( 'prosearch' , $objUser->modules );
            }
            
            $_SESSION['ps_settings'] = $arrSettings;
        }
    }

    public function setUserSettingsOnSave( $dc ) {

        $settings = array(

            'id' => $dc->activeRecord->id,
            'shortcut' => $dc->activeRecord->keyboard_shortcut ? $dc->activeRecord->keyboard_shortcut : 'alt+m',
        );

        $_SESSION['ps_settings'] = $settings;
    }

    public function getUserSettings() {

        if (TL_MODE == 'BE') {

            $settings = $_SESSION['ps_settings'];
            $settings = $settings ? $settings : array( 'shortcut' => 'alt+m' );
            $GLOBALS['TL_MOOTOOLS'][] = '<script>var UserSettings = '.json_encode($settings).';</script>';
        }
    }
}
