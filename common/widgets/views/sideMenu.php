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
    <?php if(!isset($item['enabled']) or $item['enabled']===true):?>
        <li class="treeview <?= $item['active']?'active menu-open':'' ?>">
            <a href="#">
                <?= Icon::widget(['icon'=>$item['icon']]) ?> <span><?= $item['title'] ?></span>
                <span class="pull-right-container">
                    <?= Icon::widget(['icon'=>'angle-left pull-right']) ?>
                </span>
            </a>
            <ul class="treeview-menu">

            <?php foreach ($item['items'] as $menu): ?>
                    <li class="<?= empty($menu['active']) ? '' : 'active' ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl($menu['url']) ?>">
                            <?= Icon::widget(['icon' => $menu['icon'] ? $menu['icon'] : 'link']) ?>
                            <span><?= $menu['label'] ?></span>
                        </a>
                    </li>
            <?php endforeach;?>
            </ul>
        </li>


    <?php endif;?>
<?php endforeach; ?>