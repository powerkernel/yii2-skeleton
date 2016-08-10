<?php

namespace backend\controllers;

use Yii;

/**
 * RbacController
 */
class RbacController extends BackendController
{


    /**
     * Index page
     * @return mixed
     */
    public function actionIndex()
    {
        $roles = Yii::$app->authManager->getRoles();
        return $this->render('index', ['roles' => $roles]);
    }

    /**
     * @param $user
     * @param $role
     * @return \yii\web\Response
     */
    public function actionAssign($user, $role)
    {
        $auth = Yii::$app->authManager;


        if (!$auth->getAssignment($role, $user)) {
            $r = $auth->getRole($role);
            $auth->assign($r, $user);
        }

        return $this->redirect(['/account/view', 'id' => $user]);

    }

    /**
     * @param $user
     * @param $role
     * @return \yii\web\Response
     */
    public function actionRevoke($user, $role)
    {
        /* protect yourself */
        if (Yii::$app->user->id == $user) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You can not revoke a role from yourself.'));
            return $this->redirect(['/account/view', 'id' => $user]);
        }

        /* protect no admin */
//        if($role=='admin'){
//            $totalAdmin= (new Query())->from('{{%core_auth_assignment}}')->where(['item_name'=>'admin'])->count();
//            if($totalAdmin==1){
//                Yii::$app->session->setFlash('error', Yii::t('app', 'We need at least one admin user.'));
//                return $this->redirect(['/account/view', 'id'=>$user]);
//            }
//        }


        /* revoke */
        $auth = Yii::$app->authManager;
        if ($auth->getAssignment($role, $user)) {
            $r = $auth->getRole($role);
            $auth->revoke($r, $user);
        }

        return $this->redirect(['/account/view', 'id' => $user]);
    }

}
