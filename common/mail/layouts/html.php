<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <style type="text/css">
        img {
            max-width: 100%;
        }
        body {
            -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
        }
        body {
            background-color: #f6f6f6;
        }
        @media only screen and (max-width: 640px) {
            body {
                padding: 0 !important;
            }
            h1 {
                font-weight: 800 !important; margin: 20px 0 5px !important;
            }
            h2 {
                font-weight: 800 !important; margin: 20px 0 5px !important;
            }
            h3 {
                font-weight: 800 !important; margin: 20px 0 5px !important;
            }
            h4 {
                font-weight: 800 !important; margin: 20px 0 5px !important;
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
                padding: 0 !important; width: 100% !important;
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
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
        <?= $content ?>
        <div class="footer" style="width: 100%; clear: both; color: #999; padding: 20px;">
            <table width="100%">
                <tr>
                    <td class="aligncenter content-block" style="vertical-align: top; padding: 0 0 20px; text-align: center; color: #999; font-size: 12px;" valign="top" align="center">
                        <?= Html::encode(Yii::$app->name) ?>
                    </td>
                </tr>
            </table>
        </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
