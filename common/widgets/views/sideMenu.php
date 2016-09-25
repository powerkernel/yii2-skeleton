<?php
/**
 * @var $items []
 * @var $homeTitle string
 * @var $homeUrl string
 * @var $this \yii\web\View
 */
use modernkernel\fontawesome\Icon;
$this->registerJs('$(".nav-home").on("click", function(){window.location.replace($(this).data("url"));})');
?>
<?php if(!empty($homeTitle) && !empty($homeUrl)):?>
<li class="header nav-home" data-url="<?= $homeUrl ?>" style="cursor: pointer;"><?= $homeTitle ?></li>
<?php endif;?>
<?php foreach ($items as $item): ?>

        <?php if(!empty($item['url'])):?>
            <li class="<?= empty($item['active']) ? '' : 'active' ?>">
            <a href="<?= Yii::$app->urlManager->createUrl($item['url']) ?>">
                <?= Icon::widget(['icon' => $item['icon'] ? $item['icon'] : 'link']) ?>
                <span><?= $item['label'] ?></span>
            </a>
        </li>
        <?php else :?>
        <li class="header">
            <?= $item['label'] ?>
        </li>
        <?php endif;?>
    </li>
<?php endforeach; ?>