<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace backend\controllers;

use common\models\Blog;
use common\models\Content;
use Yii;
use common\models\BlogSearch;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends BackendController
{


    /**
     * Manage blog posts
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUpdate($id)
    {
        return $this->redirect(Yii::$app->urlManagerFrontend->createUrl(['/blog/update', 'id' => $id]));
    }

    /**
     * @param $name
     * @return \yii\web\Response
     */
    public function actionView($name)
    {
        return $this->redirect(Yii::$app->urlManagerFrontend->createUrl(['/blog/view', 'name' => $name]));
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = Blog::findOne($id);
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }


}
