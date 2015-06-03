<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib\Helper;

use Cake\Utility\Inflector;

class StringHelper extends AppHelper
{

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
        $slug = Inflector::slug($text);
        if ($length) {
            $slug = substr($slug, 0, $length);
        }

        return $slug;
    }

}
