<?php

/* @var $this yii\web\View */
use modernkernel\fontawesome\Icon;

/* @var $files [] */
/* @var $v [] */


$this->title = Yii::t('app', 'Dashboard');
//$this->params['subtitle']='v1.0';
//$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => '#home'];
?>
<div class="site-index">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('app', 'Application Information') ?></h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <tr>
                                <th>Name</th>
                                <td><?= Yii::$app->name ?></td>
                            </tr>
                            <tr>
                                <th>Version</th>
                                <td><?= $v['version'] ?> build <?= $v['build'] ?></td>
                            </tr>
                            <tr>
                                <th>Release date</th>
                                <td><?= Yii::$app->formatter->asDate($v['date']) ?></td>
                            </tr>


                            <tr>
                                <th>Developer</th>
                                <td>Harry Tang (harry@modernkernel.com)</td>
                            </tr>
                            <tr>
                                <th>Powered by</th>
                                <td>modernkernel.com</td>
                            </tr>
                            <tr>
                                <th>Support</th>
                                <td>modernkernel.com</td>
                            </tr>


                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('app', 'System Information') ?></h3>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <tr>
                                <th>Operating System</th>
                                <td><?= php_uname() ?></td>
                            </tr>
                            <tr>
                                <th>Software</th>
                                <td><?= $_SERVER['SERVER_SOFTWARE'] ?></td>
                            </tr>
                            <tr>
                                <th>Database</th>
                                <td>
                                    <?php if(!empty(Yii::$app->db->driverName)):?>
                                        <?= Yii::$app->db->driverName=='mysql'?'MySQL':Yii::$app->db->driverName ?>
                                    <?php endif;?>
                                    <?= !empty(Yii::$app->mongodb)?'MongoDB':'' ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Yii Version</th>
                                <td><?= Yii::getVersion() ?></td>
                            </tr>


                            <tr>
                                <th>Environment</th>
                                <td><?= YII_ENV ?></td>
                            </tr>
                            <tr>
                                <th>Debug Mode</th>
                                <td><?= Yii::$app->formatter->asBoolean(YII_DEBUG) ?></td>
                            </tr>


                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('app', 'Images files verification') ?></h3>
        </div>
        <div class="box-body padding">
            <?php foreach ($files as $file): ?>
                <div>
                    <?php if($file['exist']):?>
                        <code class="text-green"><?= $file['file'] ?></code>
                        <?= Icon::widget(['icon'=>'check text-green']) ?>
                    <?php else:?>
                        <code class="text-red"><?= $file['file'] ?></code>
                        <?= Icon::widget(['icon'=>'exclamation-triangle text-red']) ?>
                    <?php endif;?>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
