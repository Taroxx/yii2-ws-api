yii2 plugin for wheel-size.com API
==================================
yii2 plugin for wheel-size.com API

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).


To install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/Taroxx/yii2-wsapi.git"
    },
    {
      "type": "git",
      "url": "https://github.com/driveate/ws-api-client-php.git"
    }
  ],
  "minimum-stability":"dev",
  "require": {
    "taroxx/yii2-wsapi": "*@dev"
  }
}

```

Then run `composer install` or `composer update`


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \taroxx\wsapi\AutoloadExample::widget(); ?>```