<?php

namespace GlpiPlugin\Techtask;

use CommonGLPI;
use Plugin;

class Menu extends CommonGLPI
{
    public static function getMenuName()
    {
        return __('TechTask', 'techtask');
    }

    public static function getMenuContent()
    {
        return [
            'title' => self::getMenuName(),
            'page'  => Plugin::getWebDir('techtask') . '/front/form.php',
            'icon'  => 'fas fa-tasks',
        ];
    }
}
