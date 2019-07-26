Yii 2 Skeleton
==============

[![Greenkeeper badge](https://badges.greenkeeper.io/powerkernel/yii2-skeleton.svg)](https://greenkeeper.io/)

Yii 2 Skeleton is a skeleton (based on [Yii 2 App Advanced](https://github.com/yiisoft/yii2-app-advanced)) application best for
developing complex Web applications with multiple tiers.

The template includes 4 tiers: front end, back end, api and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Note: Yii 2 Skeleton only supports MongoDB

INSTALLATION
------------
0. Unzip repository, edit composer.json to suit your needs, then run `composer update` or `composer update --prefer-source`
1. Run `init`, update your database information, `baseUrl` in common/config/mail-local.php
2. Run `php yii mongodb-migrate --migrationPath=@console/migrations`
3. Run `php yii setup`
4. Go to frontend and sign up for new account, admin role will be auto assigned
5. Go to backend and update all settings (reCaptcha, API, SMTP...)
6. Generate icons from http://realfavicongenerator.net/, upload favicon.ico to the root of your web site (frontend and backend)
8. (optional) update `gitHubPage` in common/config/params-local.php if you want to use github to host favicon and images.

## Web server config
frontend/web => domain.com
backend/web => backend.domain.com
api/web => api.domain.com

## Google Login Authorized redirect URIs
https://domain.com/account/auth?authclient=google
https://backend.domain.com/account/login/google
