<?php
/**
 * @var $items []
 * @var $this \yii\web\View
 */
use modernkernel\fontawesome\Icon;
$this->registerJs('$(".admincp").on("click", function(){window.location.replace($(this).data("url"));})');
?>
<li class="header admincp" data-url="<?= Yii::$app->homeUrl ?>" style="cursor: pointer;">Admin CP</li>
<?php foreach ($items as $item): ?>
    <li class="<?= $item['active'] ? 'active' : '' ?>">
        <a href="<?= Yii::$app->urlManager->createUrl($item['url']) ?>">
            <?= Icon::widget(['icon' => $item['icon'] ? $item['icon'] : 'link']) ?>
            <span><?= $item['label'] ?></span>
        </a>
    </li>
<?php endforeach; ?>