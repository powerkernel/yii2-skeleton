<?php
use modernkernel\fontawesome\Icon;

?>
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?= Yii::$app->homeUrl ?>" class="navbar-brand"><?= Yii::$app->name ?></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <?= Icon::widget(['icon' => 'bars']) ?>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?= Yii::$app->homeUrl ?>">Home<span
                                class="sr-only">(current)</span></a></li>
                    <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/about']) ?>">About</a></li>
                    <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/contact']) ?>">Contact</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->

            <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>