<?php

namespace Alnv\ProSearchBundle\Classes;

class InitializeProSearch
{
    private string $strPlaceholder = '<!-- ### %PROSEARCH_SCRIPT_TAG% ### -->';

    public function setScriptPlaceholder(): void
    {
        $GLOBALS['TL_MOOTOOLS'][] = $this->strPlaceholder;
    }
}
