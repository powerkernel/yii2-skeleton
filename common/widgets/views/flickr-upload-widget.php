<?php
use yii\helpers\Html;

/* @var \common\components\FlickrPhoto $client */
/* @var \common\models\Service $flickr */
/* @var \yii\web\View $this */

$js=file_get_contents(__DIR__ . '/flickr-upload-widget.min.js');
$this->registerJs($js);

$flickrPhotoUrl=Yii::$app->urlManager->createUrl(['/site/flickr-photo']);
$flickrDeleteUrl=Yii::$app->urlManager->createUrl(['/site/flickr-delete']);
$endpoint=Yii::$app->urlManager->createUrl(['/site/flickr-upload']);
echo Html::hiddenInput('url-load-flickr-photo', $flickrPhotoUrl, ['id' => 'url-load-flickr-photo']);
echo Html::hiddenInput('url-load-flickr-delete', $flickrDeleteUrl, ['id' => 'url-load-flickr-delete']);
?>
<div id="flickr-photos-container" style="margin-bottom: 10px;">

</div>
<div>
    <?=
    modernkernel\fineuploader\Fineuploader::widget([
        'dropLabel'=>Yii::t('app', 'Drag & drop photos here'),
        'buttonLabel'=>Yii::t('app', 'Choose photos'),
        'options' => [
            'request' => [
                'endpoint' => $endpoint,
                'params' => [Yii::$app->request->csrfParam => Yii::$app->request->csrfToken]
            ],
            'validation' => [
                'allowedExtensions' => ['jpeg', 'jpg', 'png', 'bmp', 'gif'],
            ],
            'classes' => [
                'success' => 'alert alert-success hidden',
                'fail' => 'alert alert-error'
            ],
            // other options like
            //'multiple'=>false,
            //'autoUpload'=>false
        ],
        'events' => [
            'allComplete' => 'loadFlickrPhoto()',
        ]
    ])
    ?>
</div>
