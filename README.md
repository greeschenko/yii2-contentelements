# yii2-contentelements
======================
Highly variable content elements module help you add static pages, news, posts, etc to your projects


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

add

```
"repositories": [
    ...
    {
        "type": "git",
        "url": "https://github.com/greeschenko/yii2-contentelements.git"
    }
    ...
],
```

```
    "require": {
        ...
        "greeschenko/yii2-contentelements": "*"
        ...
    },
```

to the `composer.json` file.


update database

$ php yii migrate/up --migrationPath=@vendor/greeschenko/yii2-contentelements/migrations


Usage
-----

add to you app config

```
...
'urlManager' => [
    ...
    'rules' => array(
        '<req:[A-Za-z0-9-/]+>.html' => '/pages',
        ...
    ),
],
...

'modules'=>[
    ...


    'pages' => [
        'class' => 'greeschenko\contentelements\Module',
        'userclass' => 'app\models\User', //you can use your user model with 'role' => 'admin'
    ],
    ...
],

```

frontend  /pages or /{urld}.html

backend  /pages/elements

more info in [wiki](https://github.com/greeschenko/yii2-contentelements/wiki)
