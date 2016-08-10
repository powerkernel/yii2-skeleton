<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

/* @var $this \yii\web\View */
/* @var $roles yii\rbac\Role[] */

$this->title = Yii::t('app', 'RBAC - Role-based Access Control');
$keywords = '';
$description = '';

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $description]);
//$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow, nosnippet, noodp, noarchive, noimageindex']);

/* Facebook */
//$this->registerMetaTag(['property' => 'og:title', 'content' => $this->title]);
//$this->registerMetaTag(['property' => 'og:description', 'content' => $description]);
//$this->registerMetaTag(['property' => 'og:type', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:image', 'content' => '']);
//$this->registerMetaTag(['property' => 'og:url', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:app_id', 'content' => '']);
//$this->registerMetaTag(['property' => 'fb:admins', 'content' => '']);

/* Twitter */
//$this->registerMetaTag(['name'=>'twitter:title', 'content'=>$this->title]);
//$this->registerMetaTag(['name'=>'twitter:description', 'content'=>$description]);
//$this->registerMetaTag(['name'=>'twitter:card', 'content'=>'summary']);
//$this->registerMetaTag(['name'=>'twitter:site', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:image', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label1', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:data2', 'content'=>'']);
//$this->registerMetaTag(['name'=>'twitter:label2', 'content'=>'']);

/* breadcrumbs */
$this->params['breadcrumbs'][] = Yii::t('app', 'RBAC');


?>
<div class="rbac-index">
    <div class="box box-primary">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr role="row">
                        <th><?= Yii::t('app', 'Name') ?></th>
                        <th><?= Yii::t('app', 'Type') ?></th>
                        <th><?= Yii::t('app', 'Description') ?></th>
                        <th><?= Yii::t('app', 'Rule') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($roles as $i => $role): ?>
                        <tr role="row" class="<?= ($i % 2) ? 'odd' : 'even' ?>">
                            <td class="sorting_1"><?= ucfirst($role->name) ?></td>
                            <td><?= $role->type == \yii\rbac\Role::TYPE_ROLE ? Yii::t('app', 'Role') : Yii::t('app', 'Permission') ?></td>
                            <td><?= $role->description ?></td>
                            <td><?= $role->ruleName ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>