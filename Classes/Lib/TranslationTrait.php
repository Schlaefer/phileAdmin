<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

trait TranslationTrait {

    public function trans($string) {
        return $this->app['translator']->trans($string);
    }

}
