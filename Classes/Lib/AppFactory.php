<?php

namespace Phile\Plugin\Siezi\PhileAdmin\Lib;

use Phile\Plugin\Siezi\PhileAdmin\Lib\Helper\StringHelper;
use Phile\Plugin\Siezi\PhileAdmin\Lib\Helper\UrlHelper;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class AppFactory
{

    use ControllerTrait;
    use TranslationTrait;

    /**
     * @param $Plugin
     * @return Application
     */
    public function create($Plugin)
    {
        $this->app = $app = new Application();
        $app['plugin'] = $Plugin;

        if ($app['plugin']->getSetting('debug')) {
            $app['debug'] = true;
        }

        $this->initServices($app);
        $this->initRoutes($app);
        $this->initHelpers($app);
        $this->initTranslations($app);

        return $app;
    }

    protected function initServices($app)
    {
        $app->register(new \Silex\Provider\FormServiceProvider());
        $app->register(new \Silex\Provider\SessionServiceProvider());
        $app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

        $appBasePath = $app['plugin']->getSetting('appBasePath');
        $admin = $app['plugin']->getSetting('admin');
        $users = [];
        if (!empty($admin['password'])) {
            $users[$admin['name']] = [
              'ROLE_ADMIN',
              $admin['password']
            ];
        }
        $app->register(new \Silex\Provider\SecurityServiceProvider(), [
          'security.firewalls' => [
              'login' => [
                    'pattern' => '^' . $appBasePath . '/login$'
              ],
            'secured' => [
              'pattern' => '^.*$',
              'form' => [
                'login_path' => $appBasePath . '/login',
                'check_path' => $appBasePath . '/login_check',
                'target_url' => $appBasePath . '/'
              ],
              'logout' => [
                'logout_path' => $appBasePath . '/logout',
                'target_url' => $appBasePath . '/'
              ],
              'users' => $users
            ]
          ]
        ]);

        $app['security.encoder.digest'] = $app->share(function () {
            return new BCryptPasswordEncoder(12);
        });

        $app->register(new TwigServiceProvider(), [
          'twig.options' => ['cache' => $app['debug'] ? false : CACHE_DIR],
          'twig.path' => $app['plugin']->getPluginPath('templates')
        ]);

        $app['adminPlugin_factory'] = function($app) {
            return new AdminPlugin($app);
        };
    }

    protected function initRoutes($app)
    {
        $appBasePath = $app['plugin']->getSetting('appBasePath');
        $app->get($appBasePath . '/', function () use ($app) {
            return $app->redirect('start');
        });

        //= login handling
        $app->match($appBasePath . '/login', function (Request $request) use ($app) {
            $error = $app['security.last_error']($request);
            if ($error) {
                $this->flash($this->trans($error), 'error');
            }

            $admin = $app['plugin']->getSetting('admin');
            if (empty($admin['password'])) {
                $vars['generatePassword'] = true;
                $vars['password'] = '';
            }
            if ($pw = $request->get('password')) {
                $vars['password'] = $pw;
                $encoder = new BCryptPasswordEncoder(12);
                $vars['hash'] = $encoder->encodePassword($pw, null);
            }

            $vars['last_username'] = $app['session']->get('_security.last_username');
            return $app['twig']->render('login/login.twig', $vars);
        })->bind('login');

        $app->post($appBasePath . '/login_check', function () use ($app) {
            $url = $app['plugin']->getSetting('appBaseUrl') . '/';
            return $app->redirect($url);
        })->bind('login_check');

        $app->get($appBasePath . '/logout', function () use ($app) {
            $url = $app['plugin']->getSetting('appBaseUrl') . '/';
            return $app->redirect($url);
        })->bind('logout');
    }

    protected function initHelpers($app)
    {
        $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
            $twig->addExtension(new StringHelper($app));
            $twig->addExtension(new UrlHelper($app));
            return $twig;
        }));

    }

    protected function initTranslations($app) {
        $app->register(new \Silex\Provider\TranslationServiceProvider(), [
          'locale_fallbacks' => ['en'],
        ]);
        $app['translator']->setLocale($app['plugin']->getSetting('lang'));
        $app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
            $translator->addLoader('php', new PhpFileLoader());
           return $translator;
        }));
    }

}