<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

trait ControllerTrait {

    protected function flash($message, $type = 'info') {
        $type = ($type === 'error') ? 'danger' : $type;
        $this->app['session']->getFlashBag()->add($type, $message);
    }

    protected function redirect($url)
    {
        $url = ltrim($url, '/');
        $url = $this->app['plugin']->getSetting('appBaseUrl') . '/' . $url;
        return $this->app->redirect($url);
    }

}