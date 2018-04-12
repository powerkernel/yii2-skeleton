<?php
/**
 * @var $items []
 * @var $homeTitle string
 * @var $homeUrl string
 * @var $this \yii\web\View
 */
use powerkernel\fontawesome\Icon;
$this->registerJs('$(".nav-home").on("click", function(){window.location.replace($(this).data("url"));})');
?>
<?php if(!empty($homeTitle) && !empty($homeUrl)):?>
<li class="header nav-home" data-url="<?= $homeUrl ?>" style="cursor: pointer;"><?= $homeTitle ?></li>
<?php endif;?>

<?php foreach ($items as $item): ?>
    <?php if(!isset($item['enabled']) or $item['enabled']===true):?>
        <li class="treeview <?= $item['active']?'active menu-open':'' ?>">
            <a href="#">
                <?= Icon::widget(['prefix'=>isset($item['prefix'])?$item['prefix']:'fas fa-fw', 'name'=>$item['icon']]) ?> <span class="treeview-label"><?= $item['title'] ?></span>
                <span class="pull-right-container">
                    <?php //Icon::widget(['prefix'=>'fas', 'name'=>'angle-left', 'options'=>['class'=>'pull-right']]) ?>
                </span>
            </a>
            <ul class="treeview-menu">
            <?php foreach ($item['items'] as $menu): ?>
                <?php if(!isset($menu['enabled']) or $menu['enabled']===true):?>
                    <li class="<?= empty($menu['active']) ? '' : 'active' ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl($menu['url']) ?>">
                            <?= Icon::widget(['prefix'=>isset($menu['prefix'])?$menu['prefix']:'fas fa-fw', 'name' => $menu['icon'] ? $menu['icon'] : 'link']) ?>
                            <span class="treeview-label"><?= $menu['label'] ?></span>
                        </a>
                    </li>
                <?php endif;?>
            <?php endforeach;?>
            </ul>
        </li>


    <?php endif;?>
<?php endforeach; ?>
