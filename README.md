Port Your Data with Porter
==========================
Porter allows you to create 'containers' of your data files and sql which you can save and restore

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist auzadventure/yii2-porter "*"
```

or add

```
"auzadventure/yii2-porter": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \auzadventure\porter\AutoloadExample::widget(); ?>```