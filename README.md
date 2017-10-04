Yii 2 Skeleton
==============

Yii 2 Skeleton is a skeleton (based on [Yii 2 App Advanced](https://github.com/yiisoft/yii2-app-advanced)) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Yii2 App Advanced Documentation is at [docs/guide/README.md](https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/README.md).

## Installing using Composer

If you do not have [Composer](http://getcomposer.org/), follow the instructions in the
[Installing Yii](https://github.com/yiisoft/yii2/blob/master/docs/guide/start-installation.md#installing-via-composer) section of the definitive guide to install it.

With Composer installed, you can then install the application using the following commands:

    composer global require "fxp/composer-asset-plugin:~1.2.0"
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
    Login with Facebook, Google
    CRUD: View, Update, Delete, List
RBAC
    Assign, Revoke, List
I18N
    Message Translation
    User locale selection    
```

And adding more features...
```
More comming soon..
```

HOW TO USE
----------
1. Run `init`, update your database information in common\config\mail-local.php
2. Run `php yii migrate` or `php yii mongodb-migrate --migrationPath=@console/migrations/mongodb`
3. Run `php yii setup`
4. Config/Symlink frontend\web & backend\web to your public_html
5. Go to frontend and sign up for new account, admin role will be auto assigned.
6. Go to backend and update all settings (reCaptcha, API, SMTP...)
7. Generate icons from http://realfavicongenerator.net/
8. Your are ready!

CONFIG
------
1. Replace your icons (apple-touch-icon-57x57.png, apple-touch-icon-60x60.png, favicon-32x32.png etc)
2. Replace your logos in /images
3. Ignore local composer.json: `git update-index --skip-worktree composer.json`
4. Ignore local site/index: `git update-index --skip-worktree frontend/views/site/index.php`
5. Ignore local css: `git update-index --skip-worktree frontend/web/css/style.css.map`
`git update-index --skip-worktree frontend/web/css/style.scss`
`git update-index --skip-worktree frontend/web/css/style.css`

## Symlink Help
For Linux
```ln -s ~/domains/domain.com/backend/web/ ~/domains/domain.com/frontend/web/backend```
```ln -s ~/domains/domain.com/frontend/web/ ~/domains/domain.com/public_html```


For windows
```mklink /D PATH_TO\frontend\web\backend PATH_TO\backend\web```

Google Login Authorized redirect URIs
https://DOMAIN.COM/account/login/google
https://DOMAIN.COM/account/auth?authclient=google
https://DOMAIN.COM/backend/account/login/google
https://DOMAIN.COM/backend/account/auth?authclient=google

https://DOMAIN.DEV/account/login/google
https://DOMAIN.DEV/account/auth?authclient=google
https://DOMAIN.DEV/backend/account/login/google
https://DOMAIN.DEV/backend/account/auth?authclient=google