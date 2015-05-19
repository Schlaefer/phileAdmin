<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib\Helper;

abstract class AppHelper extends \Twig_Extension {

    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

}
