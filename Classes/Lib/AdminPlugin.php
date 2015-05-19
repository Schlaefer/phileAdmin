<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

use Silex\Application;

class AdminPlugin
{

    use TranslationTrait;

    protected $app;

    protected $routes;

    protected $menu = [];

    protected $templateFolder;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getTitle()
    {
        if (empty($this->menu['title'])) {
            return null;
        }
        return $this->trans($this->menu['title']);
    }

    public function getUrl()
    {
        if (empty($this->menu['url'])) {
            return null;
        }
        return $this->menu['url'];
    }

    public function setMenu($title, $url)
    {
        $this->menu = [
          'title' => $title,
          'url' => $url
        ];
        return $this;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getTemplateFolder()
    {
        return $this->templateFolder;
    }

    public function setTemplateFolder($folder)
    {
        $this->templateFolder = $folder;
        return $this;
    }

    public function setRoutes(array $routes)
    {
        foreach ($routes as $key => $value) {
            $value->initialize(['plugin' => $this]);
            $newKey = $this->app['plugin']->getSetting('appBasePath') . $key;
            $routes[$newKey] = $value;
            unset($routes[$key]);
        }
        $this->routes = $routes;
        return $this;
    }

    public function setLocalesFolder($folder)
    {
        // @todo performance: only load required lang files
        $files = glob($folder . '/*.php');
        $this->app['translator'] = $this->app->share(
          $this->app->extend('translator', function ($translator) use ($files) {
              foreach ($files as $file) {
                  $lang = basename($file, '.php');
                  $translator->addResource('php', $file, $lang);
              }

              return $translator;
          }));
        return $this;
    }

}