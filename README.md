Yii 2 Skeleton
==============

Yii 2 Skeleton is a skeleton (based on [Yii 2 App Advanced](https://github.com/yiisoft/yii2-app-advanced)) application best for
developing complex Web applications with multiple tiers.

The template includes 4 tiers: front end, back end, api and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Note: Yii 2 Skeleton only supports MongoDB

## Installing using Composer

If you do not have [Composer](http://getcomposer.org), follow the instructions in the
[Installing Yii](https://github.com/yiisoft/yii2/blob/master/docs/guide/start-installation.md#installing-via-composer) section of the definitive guide to install it.

With Composer installed, you can then install the application using the following commands:

    composer create-project --prefer-dist powerkernel/yii2-skeleton yii-application
or if you want to install packages from ```source```

    composer create-project --prefer-source powerkernel/yii2-skeleton yii-application


INSTALLATION
------------
1. Run `init`, update your database information in common\config\mail-local.php
2. Run `php yii mongodb-migrate --migrationPath=@console/migrations`
3. Run `php yii setup`
4. Config/Symlink frontend\web & backend\web to your public_html
5. Go to frontend and sign up for new account, admin role will be auto assigned.
6. Go to backend and update all settings (reCaptcha, API, SMTP...)
7. Generate icons from http://realfavicongenerator.net/
8. Your are ready!

CONFIG
------
1. Go to [realfavicongenerator](http://realfavicongenerator.net) for icons creation, then upload them to frontend/web/favicon
2. Upload favicon.ico to the root of your web site (frontend and backend)
3. Upload your logos to frontend/web/images (logo.png, banner.svg, logo-mini.svg, logo-lg.svg, logo-1024.png, logo-120.png)
4. Ignore local composer.json: `git update-index --skip-worktree composer.json`
5. Ignore localhost.php (if you have this file, all mails will be sent to a file, delete it when go live): `git update-index --skip-worktree common/config/localhost.php`
6. Ignore local site/index: `git update-index --skip-worktree frontend/views/site/index.php`

## symlink public_html help
Linux public_html
```ln -s ~/domains/domain.com/frontend/web/ ~/domains/domain.com/public_html```

## Google Login Authorized redirect URIs
https://domain.com/account/login/google
https://domain.com/account/auth?authclient=google
https://domain.com/backend/account/login/google
https://domain.com/backend/account/auth?authclient=google

## Google Login Authorized redirect URIs (for localhost)
https://domain.local/account/login/google
https://domain.local/account/auth?authclient=google
https://domain.local/backend/account/login/google
https://domain.local/backend/account/auth?authclient=google
