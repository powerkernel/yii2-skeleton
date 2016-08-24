Yii 2 Skeleton
==============

Yii 2 Skeleton is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

## Installing using Composer

If you do not have [Composer](http://getcomposer.org/), follow the instructions in the
[Installing Yii](https://github.com/yiisoft/yii2/blob/master/docs/guide/start-installation.md#installing-via-composer) section of the definitive guide to install it.

With Composer installed, you can then install the application using the following commands:

    composer global require "fxp/composer-asset-plugin:~1.1.1"
    composer create-project -s dev --prefer-dist modernkernel/yii2-skeleton yii-application
or if you want to install packages from ```source```

    composer create-project -s dev --prefer-source modernkernel/yii2-skeleton yii-application

The first command installs the [composer asset plugin](https://github.com/francoispluchino/composer-asset-plugin/)
which allows managing bower and npm package dependencies through Composer. You only need to run this command
once for all. The second command installs the advanced application in a directory named `yii-application`.
You can choose a different directory name if you want.

DIRECTORY STRUCTURE
-------------------

```
common
    bootstrap/           contains bootstrap 
    config/              contains shared configurations
    gii/                 contains gii templates
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    plugins/             contains 3rd plugins used in both backend and frontend
    widgets/             contains widgets classes used in both backend and frontend    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```

FEATURES
--------

```
Account
    Signup, Login
    CRUD: View, Update, Delete, List
RBAC
    Assign, Revoke, List
I18N
    Message Translation
    User locale selection    
```

And adding more features...
```
??
```

HOW TO USE
----------
1. Update your database information in common\config\mail-local.php
2. Run `php yii migrate`
3. Config/Symlink frontend\web & backend\web to your public_html
4. Go to frontend and sign up for new account, admin role will be auto assigned.
5. Go to backend and update all settings (reCaptcha, API, SMTP...)
6. Your are ready!

## Symlink Help
For Linux
```ln -s PATH_TO/frontend/web/backend PATH_TO/backend/web```
For windows
```mklink /D PATH_TO\frontend\web\backend PATH_TO\backend\web```