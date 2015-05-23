<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

trait TranslationTrait {

    protected function trans($string) {
        return $this->app['translator']->trans($string);
    }

}
