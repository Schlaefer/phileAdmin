<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib\Helper;

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

    public static function slug($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

}
