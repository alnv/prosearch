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


/**
 * Class UserSettings
 * @package ProSearch
 */
class UserSettings{


    /**
     * set user setting on login
     */
    public function setUserSettingsOnLogin(BackendUser $user)
    {

        if ($user instanceof BackendUser) {

            $settings = array(
                'id' => $user->id,
                'shortcut' => $user->keyboard_shortcut ? $user->keyboard_shortcut : 'alt+m',
            );

            $_SESSION['ps_settings'] = $settings;
        }

    }

    /**
     * set user setting on login
     */
    public function setUserSettingsOnSave($dc)
    {
        $settings = array(
            'id' => $dc->activeRecord->id,
            'shortcut' => $dc->activeRecord->keyboard_shortcut ? $dc->activeRecord->keyboard_shortcut : 'alt+m',
        );

        $_SESSION['ps_settings'] = $settings;
    }

    /**
     *
     */
    public function getUserSettings()
    {

        if(TL_MODE == 'BE')
        {
            $settings = $_SESSION['ps_settings'];
            $settings = $settings ? $settings : array( 'shortcut' => 'alt+m' );
            $GLOBALS['TL_MOOTOOLS'][] = '<script>var UserSettings = '.json_encode($settings).';</script>';

        }
    }

}
