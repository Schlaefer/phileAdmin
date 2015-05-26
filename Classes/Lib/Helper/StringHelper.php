<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib\Helper;

use Cocur\Slugify\Slugify;

class StringHelper extends AppHelper
{

    private static $slug;

    public function getName()
    {
        return 'StringHelper';
    }

    public function getFunctions()
    {
        return [
          new \Twig_SimpleFunction('String_slug', ['Phile\Plugin\Siezi\PhileAdmin\Lib\Helper\StringHelper', 'slug']),
        ];
    }

    public static function slug($text, $length = null)
    {
        if (empty(static::$slug)) {
            static::$slug = new Slugify();
        }
        $slug = static::$slug->slugify($text);
        if ($length) {
            $slug = substr($slug, 0, $length);
        }

        return $slug;
    }

}
