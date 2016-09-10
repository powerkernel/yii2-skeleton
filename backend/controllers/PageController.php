<?php

namespace backend\controllers;

use common\models\Message;
use common\models\Page;
use common\models\PageDataSearch;
use Yii;
use common\models\PageData;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for PageData model.
 */
class PageController extends BackendController
{


    /**
     * Lists all PageData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PageData model.
     * @param integer $id_page
     * @param string $language
     * @return mixed
     */
    public function actionView($id_page, $language)
    {
        //return $this->redirect(['/page/main/view', 'id'=>$id_page, 'language'=>$language]);
        $model = $this->findModel($id_page, $language);
        if($model){

            return $this->render('view', [
                'model' => $model
            ]);
        }
//        if (!Yii::$app->user->can('ownAction', ['model' => $model])) {
//            throw new ForbiddenHttpException('You are not allowed to perform this action.');
//        }

    }

    /**
     * Creates a new PageData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PageData();
        $page = new Page();
        $model->setScenario('create');

        if (Yii::$app->request->isPost) {
            $page->load(Yii::$app->request->post());
            $model->load(Yii::$app->request->post());
            if ($page->validate()) {
                $model->id_page = $page->id;
                if ($page->save() && $model->save()) {
                    return $this->redirect(['index']);
                }

            }
        }


        return $this->render('create', [
            'page' => $page,
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing PageData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_page
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($id_page, $language)
    {
        $model = $this->findModel($id_page, $language);
        $model->setScenario('update');

        //if (!Yii::$app->user->can('ownAction', ['model' => $model])) {
        //    throw new ForbiddenHttpException('You are not allowed to perform this action.');
        //}

        /* copy to other languages */
        $languages = Message::getLocaleList();
        $q = new Query();
        $pageLang = $q->select(['language'])->from('{{%core_page_data}}')->where(['id_page' => $id_page])->all();
        foreach ($pageLang as $pl) {
            if (in_array($pl['language'], array_keys($languages))) {
                unset($languages[$pl['language']]);
            }
        }
        //var_dump($languages);


        /* submitted */
        if (Yii::$app->request->isPost) {
            /* page data */
            $model->load(Yii::$app->request->post());
            $model->page->load(Yii::$app->request->post());
            $model->page->save();
            $model->save();
            return $this->redirect(['update', 'id_page' => $model->id_page, 'language' => $model->language]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'languages' => $languages
            ]);
        }
    }

    /**
     * add page language
     * @param $id
     * @param $from
     * @param $to
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAddLanguage($id, $from, $to)
    {
        $source = $this->findModel($id, $from);

        $model = new PageData();
        $model->id_page = $id;
        $model->language = $to;
        $model->title = $source->title;
        $model->description = $source->description;
        $model->content = $source->content;
        $model->keywords = $source->keywords;
        $model->status = PageData::STATUS_INACTIVE;
        if ($model->save()) {
            return $this->redirect(['update', 'id_page' => $model->id_page, 'language' => $model->language]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app','Sorry, there was a problem processing your request.'));
            return $this->redirect(['update', 'id_page' => $model->$id, 'language' => $from]);
        }
    }

    /**
     * Deletes an existing PageData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_page
     * @param string $language
     * @return mixed
     */
    public function actionDelete($id_page, $language)
    {
        $model = $this->findModel($id_page, $language);
        //if (!Yii::$app->user->can('ownAction', ['model' => $model])) {
        //    throw new ForbiddenHttpException('You are not allowed to perform this action.');
        //}
        $model->delete();

        return $this->redirect(['index']);
    }



    /**
     * Finds the PageData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_page
     * @param string $language
     * @return PageData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_page, $language)
    {
        if (($model = PageData::findOne(['id_page' => $id_page, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
