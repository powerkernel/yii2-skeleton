<?php

use common\Core;
use common\models\Setting;
use powerkernel\fontawesome\Icon;
use yii\bootstrap\Nav;

$url = Core::getStorageUrl();

?>
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?= Yii::$app->homeUrl ?>" title="<?= Yii::$app->name ?>" class="navbar-brand"
                   style="max-width: 200px; padding: auto 15px;">
                    <?= \common\models\Setting::getValue('logoLg') ?>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <?= Icon::widget(['prefix'=>'fas', 'name' => 'bars']) ?>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <?=
                Nav::widget([
                    'options' => ['class' => 'navbar-nav'],
                    'items' => $items
                ]);
                ?>
                <?php if (!empty(Setting::getValue('googleCustomSearch'))): ?>
                    <form class="navbar-form navbar-left" role="search"
                          action="<?= Yii::$app->urlManager->createUrl(['/site/search']) ?>">
                        <div class="input-group input-group-sm">
                            <input name="q" value="<?= Yii::$app->request->get('q') ?>" type="text" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Search') ?>">
                            <span class="input-group-btn">
                      <button type="submit" class="btn btn-default btn-flat">
                          <?= Icon::widget(['prefix'=>'fas', 'name' => 'search']) ?>
                      </button>
                    </span>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <!-- /.navbar-collapse -->


            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <li class="<?= Core::checkMCA('', 'account', '*') ? 'active' : '' ?>">
                            <a href="<?= Yii::$app->urlManager->createUrl(['/account']) ?>">
                                <?= Icon::widget(['prefix'=>'fas', 'name' => 'user']) ?>
                                <span><?= Yii::$app->user->identity->fullname ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout']) ?>">
                                <span><?= Yii::t('app', 'Logout') ?></span>
                                <?= Icon::widget(['prefix'=>'fas', 'name' => 'sign-out']) ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(Yii::$app->user->loginUrl) ?>">
                                <?= Icon::widget(['prefix'=>'fas', 'name' => 'sign-in']) ?>
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
