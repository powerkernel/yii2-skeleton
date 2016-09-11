<?php
use common\Core;
use modernkernel\fontawesome\Icon;
use yii\bootstrap\Nav;

?>
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?= Yii::$app->homeUrl ?>" title="<?= Yii::$app->name ?>" class="navbar-brand" style="width: 60px; padding: 10px 15px;">
                    <img src="<?= Yii::$app->request->baseUrl ?>/images/logo-mini.svg" class="img-responsive"
                         alt="<?= Yii::$app->name ?>" />
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <?= Icon::widget(['icon' => 'bars']) ?>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <?=
                Nav::widget([
                    'options' => ['class' => 'navbar-nav'],
                    'items' => [
                        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
                        ['label' => Yii::t('app', 'Blog'), 'url' => ['/blog/index'], 'active'=>Core::checkMCA(null, 'blog', '*')],
                    ],
                ]);
                ?>
            </div>
            <!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <li class="<?= Core::checkMCA('', 'account', '*') ? 'active' : '' ?>">
                            <a href="<?= Yii::$app->urlManager->createUrl(['/account']) ?>">
                                <?= Icon::widget(['icon' => 'user']) ?>
                                <span><?= Yii::$app->user->identity->fullname ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout']) ?>">
                                <span><?= Yii::t('app', 'Logout') ?></span>
                                <?= Icon::widget(['icon' => 'sign-out']) ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(Yii::$app->user->loginUrl) ?>">
                                <?= Icon::widget(['icon' => 'key']) ?>
                                <span><?= Yii::t('app', 'Login') ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>