<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

use Phile\Plugin\AbstractPlugin;

trait PluginVendorLoadingTrait {

    /**
     * bootstrap local vendor autoloader on non-composer installation
     *
     * @param $autoloadPath path to composer autoload file
     * @return bool true if local autoload false otherwise
     */
    protected function initLocalVendorLoading($autoloadPath)
    {
        if (!file_exists($autoloadPath)) {
            return false;
        }
        require $autoloadPath;
        return true;
    }

}
