<?php

namespace Phile\Plugin\Siezi\PhileAdmin;

use Phile\Core\Event;
use Phile\Core\Router;
use Phile\Plugin\AbstractPlugin;
use Phile\Plugin\Siezi\PhileAdmin\Lib\AdminPluginCollection;

class Plugin extends AbstractPlugin
{

    /**
     * @var array Phile config
     */
    protected $config;

    /**
     * @var AdminPluginCollection
     */
    protected $plugins;

    protected $settings = [
      'debug' => false,
    ];

    protected $events = [
      'config_loaded' => 'onConfigLoaded',
      'after_resolve_page' => 'startApp',
      'siezi.phileAdmin.beforeAppRun' => 'bootstrapStartPage'
    ];

    public function getConfig($key)
    {
        return $this->config[$key];
    }

    public function getPluginPath($sub = '')
    {
        return parent::getPluginPath($sub);
    }

    public function getSetting($key)
    {
        return $this->settings[$key];
    }

    protected function bootstrapStartPage($eventData)
    {
        $adminPlugin = $eventData['app']['adminPlugin_factory'];
        $adminPlugin
          ->setLocalesFolder($this->getPluginPath('locales'))
          ->setMenu('siezi.phileAdmin.start.title', '/start')
          ->setRoutes(['/start' => new AdminStart()]);
        $eventData['plugins']->add($adminPlugin);
    }

    protected function onConfigLoaded($eventData)
    {
        $this->config = $eventData['config'];
    }

    protected function startApp($eventData)
    {
        $current = rtrim($eventData['pageId'], '/') . '/';
        $apiUrl = $this->settings['pageId'];
        if (strpos($current, $apiUrl) !== 0) {
            return;
        }
        $this->completeConfig();
        $this->completeSettings();

        $app = (new Lib\AppFactory())->create($this);
        $this->plugins = new AdminPluginCollection();
        $app['plugins'] = $this->plugins;
        $eventData = ['app' => $app, 'plugins' => $this->plugins];
        Event::triggerEvent('siezi.phileAdmin.beforeAppRun', $eventData);
        foreach ($this->plugins as $plugin) {
            foreach ($plugin->getRoutes() as $route => $controller) {
                $app->mount($route, $controller);
            }
        }

        $app->run();
        Event::triggerEvent('siezi.phileAdmin.afterAppRun', $eventData);
        exit;
    }

    protected function completeConfig()
    {
        $this->config += [
          'contentDir' => CONTENT_DIR
        ];
    }

    protected function completeSettings()
    {
        $router = new Router();
        $appBasePath = '/' . trim($this->settings['pageId'], '/');
        $this->settings += [
          'appPath' => $this->getPluginPath(),
          'appBasePath' => $appBasePath,
          'appBaseUrl' => $router->url($appBasePath),
          'assetBaseUrl' => $router->url('lib'),
          'baseUrl' => $router->url('plugins/siezi/phileAdmin')
        ];
    }

}
