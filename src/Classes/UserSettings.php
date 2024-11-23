<?php

namespace Alnv\ProSearchBundle\Classes;

use Contao\BackendUser;

class UserSettings
{

    private bool $blnInitialized = false;

    private string $strPlaceholder = '<!-- ### %PROSEARCH_SCRIPT_TAG% ### -->';

    public function initializeSettings($strContent, $strTemplate)
    {

        if ($strTemplate == 'be_main' && !$this->blnInitialized) {

            $objUser = BackendUser::getInstance();
            $arrSettings = [
                'enable' => true,
                'id' => $objUser->id,
                'shortcut' => $objUser->keyboard_shortcut ? $objUser->keyboard_shortcut : 'alt+m',
            ];

            if (!empty($objUser->modules) && \is_array($objUser->modules)) {
                $arrSettings['enable'] = \in_array('prosearch_settings', $objUser->modules);
            }

            $strContent = str_replace($this->strPlaceholder, '<script>var UserSettings = ' . json_encode($arrSettings) . ';</script>', $strContent);
            $this->blnInitialized = true;
        }

        return $strContent;
    }
}
