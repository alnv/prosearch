<?php

namespace Alnv\ProSearchBundle\Classes;

use Contao\StringUtil;

class PrepareDataException
{

    public function prepareDataExceptions($arr, $db, $table)
    {
        // exception for content
        if ($table == 'tl_content') {
            $ptable = $db['ptable'];
            $arr['ptable'] = $ptable ?: '';

        }

        // exception for page
        if ($table == 'tl_page') {
            $arr['ptable'] = 'tl_page';
        }

        // exception for files
        if ($table == 'tl_files') {
            $arr['docId'] = $db['path'] ?: '';
            $arr['pid'] = 0;
        }

        return $arr;
    }

    public function setCustomTitle($table, $db, $titleFields, $doTable)
    {

        if ($table == 'tl_files') {
            return $db['name'] ? $db['name'] : $db['path'];
        }

        if ($table == 'tl_content') {
            $title = 'No Title: ' . $db['id'];

            foreach ($titleFields as $field) {

                $ct = StringUtil::deserialize(($db[$field] ?? ''));

                // check if value is serialize
                if (\is_array($ct) && !empty($ct)) {
                    $meta = Helper::parseStrForMeta(($db[$field] ?? ''));
                    $db[$field] = $meta;
                }

                if (($db[$field] ?? '') && ($db[$field] ?? '') != '' && $field != 'type') {
                    return $db[$field] . ' (' . $db['type'] . ')';
                }
            }

            return $title;

        }

        return '';
    }

    public function setCustomIcon($table, $db, $dataArr, $dca)
    {
        $iconName = '';

        if ($table == 'tl_module') {
            $iconName = 'modules.svg';
        }

        if ($table == 'tl_layout') {
            $iconName = 'layout.svg';
        }

        if ($table == 'tl_style_sheet' || $table == 'tl_style') {
            $iconName = 'css.svg';
        }

        if ($table == 'tl_image_size') {
            $iconName = 'sizes.svg';
        }

        if ($table == 'tl_newsletter') {
            $iconName = 'newsletter.svg';
        }

        if ($table == 'tl_newsletter_recipients') {
            $iconName = 'member.svg';
        }

        if ($table == 'tl_files' && $db['type'] == 'file') {

            $iconName = 'files.svg';

            if ($db['extension'] == 'pdf') {
                $iconName = 'iconPDF.svg';
            }

            if ($db['extension'] == 'jpg' || $db['extension'] == 'png' || $db['extension'] == 'tif' || $db['extension'] == 'bmp' || $db['extension'] == 'svg') {
                $iconName = 'iconJPG.svg';
            }

            if ($db['extension'] == 'gif') {
                $iconName = 'iconGIF.svg';
            }

            if ($db['extension'] == 'zip' || $db['extension'] == 'rar') {
                $iconName = 'iconRAR.svg';
            }

            if ($db['extension'] == 'css') {
                $iconName = 'iconCSS.svg';
            }

            if ($db['extension'] == 'js') {
                $iconName = 'iconJS.svg';
            }

            if ($db['extension'] == 'php') {
                $iconName = 'iconPHP.svg';
            }

        }

        if ($table == 'tl_files' && $db['type'] == 'folder') {
            $iconName = 'folderC.svg';
        }

        if ($table == 'tl_page') {
            $iconName = 'regular.svg';

            if ($db['type'] == 'root') {
                $iconName = 'pagemounts.svg';
            }

            if ($db['type'] == 'forward') {
                $iconName = 'forward.svg';
            }

            if ($db['type'] == 'redirect') {
                $iconName = 'redirect.svg';
            }

            if ($db['type'] == 'error_403') {
                $iconName = 'error_403.svg';
            }

            if ($db['type'] == 'error_404') {
                $iconName = 'error_404.svg';
            }

            if ($db['type'] == 'error_404') {
                $iconName = 'error_404.svg';
            }

            if ($db['type'] == 'regular' && $db['hide'] == '1') {
                $iconName = 'regular_2.svg';
            }

        }

        return $iconName;
    }

    public function setCustomShortcut($table, $db, $dataArr, $dca)
    {
        $shortcut = '';

        if ($table == 'tl_module') {
            $shortcut = 'fe';
        }

        if ($table == 'tl_layout') {
            $shortcut = 'la';
        }

        if ($table == 'tl_style_sheet' || $table == 'tl_style') {
            $shortcut = 'css';
        }

        if ($table == 'tl_image_size') {
            $shortcut = 'si';
        }

        if ($table == 'tl_newsletter') {
            $shortcut = 'nl';
        }

        if ($table == 'tl_newsletter_recipients') {
            $shortcut = 'abo';
        }

        if ($table == 'tl_files') {
            $shortcut = 'fi';
        }

        if ($table == 'tl_files' && $db['extension'] == 'pdf') {
            $shortcut = 'pdf';
        }

        if ($table == 'tl_files' && ($db['extension'] == 'png' || $db['extension'] == 'jpg' || $db['extension'] == 'gif' || $db['extension'] == 'svg' || $db['extension'] == 'tif')) {
            $shortcut = 'img';
        }

        if ($table == 'tl_files' && ($db['extension'] == 'zip' || $db['extension'] == 'rar')) {
            $shortcut = 'zip';
        }

        return $shortcut;
    }

}