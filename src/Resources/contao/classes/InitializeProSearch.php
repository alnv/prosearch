<?php

namespace ProSearch;

class InitializeProSearch {


    private $strPlaceholder = '<!-- ### %PROSEARCH_SCRIPT_TAG% ### -->';


    public function setScriptPlaceholder() {

        $GLOBALS['TL_MOOTOOLS'][] = $this->strPlaceholder;
    }
}
