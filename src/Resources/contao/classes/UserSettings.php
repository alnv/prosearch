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
     *
     */
    public function setUserSettings(User $user)
    {

        if ($user instanceof BackendUser) {

            $settings = array(
                'id' => $user->id,
                'shortcut' => $user->keyboard_shortcut,
            );

            $_SESSION['ps_settings'] = $settings;
        }

    }

    /**
     *
     */
    public function getUserSettings()
    {
        if(TL_MODE == 'BE')
        {
            $settings = $_SESSION['ps_settings'];
            $settings = $settings ? $settings : array( 'shortcut' => 'alt+space' );
            $GLOBALS['TL_MOOTOOLS'][] = '<script>var UserSettings = '.json_encode($settings).';</script>';

        }
    }

}
