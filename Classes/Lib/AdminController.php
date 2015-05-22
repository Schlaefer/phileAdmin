<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminController implements ControllerProviderInterface
{

    use ControllerTrait;
    use TranslationTrait;

    protected $app;

    protected $plugin;

    final public function connect(Application $app)
    {
        $this->app = $app;

        $controllers = $this->app['controllers_factory'];
        $controllers->match('/', [$this, 'index']);
        $this->getRoutes($controllers);

        return $controllers;
    }

    /**
     * callback for implementations to add routes
     *
     * @param $controllers
     * @return mixed
     */
    protected function getRoutes($controllers)
    {
        return $controllers;
    }

    /**
     * @param array $params
     */
    final public function initialize(array $params)
    {
        $this->plugin = $params['plugin'];
    }

    /**
     * render a template
     *
     * @param $template
     * @param array $vars
     * @return mixed
     */
    protected function render($template, array $vars = [])
    {
        $pluginTemplateFolder = $this->plugin->getTemplateFolder();
        if (!empty($pluginTemplateFolder)) {
            $loader = new \Twig_Loader_Filesystem($pluginTemplateFolder);
            $this->app['twig']->getLoader()->addLoader($loader);
        }

        $defaults = [
            'layout' => [
                'title' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $template)
            ]
        ];
        $vars = array_replace_recursive($defaults, $vars);

        return $this->app['twig']->render($template, $vars);
    }

}
