<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib\Helper;

class UrlHelper extends AppHelper
{

    public function getName()
    {
        return 'UrlHelper';
    }

    public function getFunctions()
    {
        return [
          new \Twig_SimpleFunction('Url_asset', [$this, 'asset']),
          new \Twig_SimpleFunction('Url_backend', [$this, 'app']),
          new \Twig_SimpleFunction('Url_phile', [$this, 'phile'])
        ];
    }

    public function asset($url)
    {
        $url = ltrim($url, '/');
        return $this->app['plugin']->getSetting('assetBaseUrl') . '/' . $url;
    }

    public function phile($url)
    {
        $url = ltrim($url, '/');
        return $this->app['plugin']->getConfig('base_url') . '/' . $url;
    }

    public function app($url)
    {
        $url = ltrim($url, '/');
        return $this->app['plugin']->getSetting('appBaseUrl') . '/' . $url;
    }

}
