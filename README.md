Port Your Data with Porter
==========================
Do you use different database and data asset (imgs). For example
1. testing set 
2. development set 

It just does not make sense to have several containers.

Porter allow you to make 'containers' of  sql dumps with data and restore them instantly without the 
need of complex stuff like docker. 


Folders can be shared via git and allow teams to instantly use data-containers and database sets. 


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
use \auzadventure\porter\Porter;
```
# Load Porter 

```$Porter = new Porter;``` 

# Generate the Form To Backup

```$Porter->backupForm();```

# Generate the list of containers 

```$Porter->listContainers()```





