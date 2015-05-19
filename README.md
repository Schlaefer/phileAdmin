# Phile Admin #

## What ##

A small admin backend framework for PhileCMS based on [Silex] (symfony components) and [Bootstrap]. Takes care of the backend boilerplate (login/logout, templating, localization) and makes it easy to develop admin plugins.

## Installation

### 1.1 Installation (composer) ###

```json
"require": {
	"siezi/phile-admin": "*"
}
```

<!--

### 1.2 Installation (Download)

* Install [Phile](https://github.com/PhileCMS/Phile)
* Clone this repo into `plugins/siezi/phileMarkdownEditor`

-->

### 2. Activation

After you have installed the plugin. You need to add the following line to your `config.php` file:


	$config['plugins']['siezi\\phileAdmin'] = ['active' => true];

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

The backend is a essentially [Silex] app and a plugin repository containing admin plugins. On a callback you create a new plugin, configure it and add it to the repository. Then you create Silex routes and controllers (extending `AdminController`).

The Cache plugin shows a simple example.


[Bootstrap]: http://getbootstrap.com/
[Silex]: http://silex.sensiolabs.org/
