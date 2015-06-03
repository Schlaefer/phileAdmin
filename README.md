# Phile Admin #

## What ##

A small admin backend framework for PhileCMS based on [Silex] (symfony components) and [Bootstrap]. Takes care of the backend boilerplate (login/logout, templating, localization) and makes it easy to develop admin plugins.

[Find plugins using it on Phile’s plugin page.](https://github.com/PhileCMS/Phile/wiki/%5BCOMMUNITY%5D-Plugins#admin--backend)

## Installation

### 1.1 Installation (composer) ###

```json
"require": {
	"siezi/phile-admin": "*"
}
```

### 1.2 Installation (Download)

[Download the latest archive from the release page](https://github.com/Schlaefer/phileAdmin/releases) into `plugins/siezi/phileAdmin`.

### 2. Activation

After you have installed the plugin you activate it by adding the following line to your `config.php` file:

```php
$config['plugins']['siezi\\phileAdmin'] = ['active' => true];
```

The default backend URL is `http://…/<phile-root>/backend/`


### 3. Start ###

To login you have to chose an admin password, create an hash for it (see login page) and put it into the plugin config.

```php
$config['plugins']['siezi\\phileAdmin']['admin'] = [
	'name' => '<name>',
	'password' => '<password hash>'
];
```


### 4. Config ###

See `config.php`.

## Plugin Development ##

The backend is essentially a [Silex] app and a admin-plugin repository containing admin-plugins. On a callback in a standard Phile Plugin-class you create a new admin-plugin, configure it and add it to the repository. Then you create Silex routes and controllers (extending `AdminController`).

See the [cache plugin](https://github.com/Schlaefer/phileAdminCache) for a simple plugin implementation.

Enable the debug mode in the `config.php` when you develop (disable template cache, …).

[Bootstrap]: http://getbootstrap.com/
[Silex]: http://silex.sensiolabs.org/
