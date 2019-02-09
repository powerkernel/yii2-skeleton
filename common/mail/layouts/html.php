<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2018 Power Kernel
 */

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php $this->head() ?>
        <style media="all" type="text/css">
@media only screen and (max-width: 640px) {
  body {
    padding: 0 !important;
  }
  h1, h2, h3, h4 {
    font-weight: 800 !important;
    margin: 20px 0 5px !important;
  }
  h1 {
    font-size: 22px !important;
  }
  h2 {
    font-size: 18px !important;
  }
  h3 {
    font-size: 16px !important;
  }
  .container {
    padding: 0 !important;
    width: 100% !important;
  }
  .content {
    padding: 0 !important;
  }
  .content-wrap {
    padding: 10px !important;
  }
  .invoice {
    width: 100% !important;
  }
}
</style>
    </head>
    <body itemscope="" itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
    <?php $this->beginBody() ?>
    <table class="body-wrap" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
        <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
            <td style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top;" valign="top"></td>
            <td class="container" width="600" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0 auto !important; display: block !important; max-width: 600px !important; clear: both !important;" valign="top">
                <div class="content" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0 auto; max-width: 600px; display: block; padding: 20px;">
                    <div class="header" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 20px 0; width: 100%;">
                        <table width="100%" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                            <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                                <td class="aligncenter" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center;" valign="top" align="center">
                                    <img class="logo" height="72" src="<?= Yii::$app->params['organization']['email_brand_logo'] ?>" alt="<?= Yii::$app->name ?>" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%;">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?= $content ?>
                    <div class="footer" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; padding: 20px;">
                        <table width="100%" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                            <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                                <td class="aligncenter content-block" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; vertical-align: top; padding: 0 0 20px; text-align: center; font-size: 12px; color: #999;" valign="top" align="center">
                                    <?= Yii::$app->params['organization']['legalName'] ?>, <?= date('Y') ?>.
                                    All rights reserved.<br style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                                    <?= Yii::$app->params['organization']['address'] ?>
                                </td>
                            </tr>
                            <tr style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px;">
                                <td class="aligncenter" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; vertical-align: top; text-align: center; font-size: 12px; color: #999;" valign="top" align="center">
                                    <?php foreach (Yii::$app->params['organization']['social'] as $social=>$info):?>
                                        <a class="social" href="<?= $info['url'] ?>" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; text-decoration: underline; font-size: 12px; color: #999; padding: 0 5px;"><img height="24" src="<?= $info['icon'] ?>" alt="<?= $social ?>" style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%;"></a>
                                    <?php endforeach;?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td style="margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top;" valign="top"></td>
        </tr>
    </table>
    <?php $this->endBody() ?>
    
    </body>
    </html>
<?php $this->endPage() ?>