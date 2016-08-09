<?php
/**
 * @var $items []
 */
use modernkernel\fontawesome\Icon;

?>
<?php foreach ($items as $item): ?>
    <li class="<?= $item['active'] ? 'active' : '' ?>">
        <a href="<?= Yii::$app->urlManager->createUrl($item['url']) ?>">
            <?= Icon::widget(['icon' => $item['icon'] ? $item['icon'] : 'link']) ?>
            <span><?= $item['label'] ?></span>
        </a>
    </li>
<?php endforeach; ?>