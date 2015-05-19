<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

class AdminPluginCollection extends \ArrayObject {

    public function add(AdminPlugin $plugin) {
        $this->append($plugin);
    }

}