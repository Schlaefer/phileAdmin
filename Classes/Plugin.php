<?php

namespace Phile\Plugin\Siezi\PhileAdmin;

use Phile\Core\Event;
use Phile\Core\Router;
use Phile\Plugin\AbstractPlugin;
use Phile\Plugin\Siezi\PhileAdmin\Lib\AdminPluginCollection;
use Phile\Plugin\Siezi\PhileAdmin\Lib\PluginVendorLoadingTrait;

class Plugin extends AbstractPlugin
{

    use PluginVendorLoadingTrait;

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
        'baseUrl' => 'plugins/siezi/phileAdmin'
    ];

    protected $events = [
        'config_loaded' => 'onConfigLoaded',
        'after_resolve_page' => 'startApp',
        'siezi.phileAdmin.beforeAppRun' => 'registerStartPagePlugin'
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

    protected function registerStartPagePlugin($eventData)
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
        $local = $this->initLocalVendorLoading($this->getPluginPath('vendor/autoload.php'));
        if ($local) {
            $this->settings['assetBaseUrl'] = (new Router)->url($this->settings['baseUrl']);
        }

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
            'contentDir' => CONTENT_DIR,
            'contentExt' => CONTENT_EXT,
            'storageDir' => STORAGE_DIR,
        ];
    }

    protected function completeSettings()
    {
        $router = new Router();
        $appBasePath = '/' . trim($this->settings['pageId'], '/');
        $this->settings += [
            'appBasePath' => $appBasePath,
            'appBaseUrl' => $router->url($appBasePath),
            'assetBaseUrl' => $router->url('lib'),
            'baseUrl' => $router->url($this->settings['baseUrl'])
        ];
    }

}
