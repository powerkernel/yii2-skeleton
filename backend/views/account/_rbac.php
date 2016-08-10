<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $model \common\models\Account */

$auth = Yii::$app->authManager;
?>
<?php foreach ($auth->getRoles() as $name => $role): ?>
    <?php if (!in_array($name, $auth->defaultRoles)): ?>

        <div class="btn-group">
            <button type="button"
                    class="btn btn-sm btn-<?= $auth->checkAccess($model->id, $name) ? 'success' : 'default' ?> dropdown-toggle <?= (($auth->checkAccess($model->id, $name) && !$auth->getAssignment($name, $model->id)) || $model->id==Yii::$app->user->id) ? 'disabled' : '' ?>"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= ucfirst($name) ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php if ($auth->checkAccess($model->id, $name)): ?>
                    <li>
                        <?= Html::a(Yii::t('app', 'Revoke'), ['/rbac/revoke', 'user' => $model->id, 'role' => $name], [
                            'class' => '',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to revoke {ROLE} from this user?', ['ROLE'=>ucfirst($name)]),
                                'method' => 'post',
                            ],
                        ]) ?>
                    </li>
                <?php else: ?>
                    <li>
                        <?= Html::a(Yii::t('app', 'Assign'), ['/rbac/assign', 'user' => $model->id, 'role' => $name], [
                            'class' => '',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to assign {ROLE} to this user?', ['ROLE'=>ucfirst($name)]),
                                'method' => 'post',
                            ],
                        ]) ?>
                    </li>
                <?php endif ?>
            </ul>
        </div>

    <?php endif; ?>
<?php endforeach; ?>
