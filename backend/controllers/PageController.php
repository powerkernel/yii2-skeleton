<?php

namespace backend\controllers;

use common\models\Page;
use common\models\PageDataSearch;
use Yii;
use common\models\PageData;
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
     * @param integer $slug
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($slug, $language)
    {
        $model = $this->findModel($slug, $language);
        return $this->render('view', [
            'model' => $model
        ]);
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
            $page->slug = $model->slug;

            if ($model->validate() && $page->validate()) {
                if ($page->save() && $model->save()) {
                    return $this->redirect(['index']);
                }
            } else {
                var_dump($page->errors);
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
     * @param string $slug
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($slug, $language)
    {
        $model = $this->findModel($slug, $language);
        $model->setScenario('update');

        /* copy to other languages */

        $languages = \common\models\Message::getLocaleList();


        $pageLang = PageData::find()->where(['slug' => $slug])->select(['language'])->all();
        foreach ($pageLang as $pl) {
            if (in_array($pl->language, array_keys($languages))) {
                unset($languages[$pl->language]);
            }
        }


        /* submitted */
        if (Yii::$app->request->isPost) {
            /* page data */
            $model->load(Yii::$app->request->post());
            $model->page->load(Yii::$app->request->post());
            $model->page->save();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Page has been saved successfully.'));
            }
            return $this->redirect(['update', 'slug' => $model->slug, 'language' => $model->language]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'languages' => $languages
            ]);
        }
    }

    /**
     * add page language
     * @param string $slug
     * @param string $from
     * @param string $to
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAddLanguage($slug, $from, $to)
    {
        $source = $this->findModel($slug, $from);

        $model = new PageData();
        $model->slug = $slug;
        $model->language = $to;
        $model->title = $source->title;
        $model->description = $source->description;
        $model->content = $source->content;
        $model->keywords = $source->keywords;
        $model->status = PageData::STATUS_INACTIVE;
        if ($model->save()) {
            return $this->redirect(['update', 'slug' => $model->slug, 'language' => $model->language]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, there was a problem processing your request.'));
            return $this->redirect(['update', 'slug' => $model->slug, 'language' => $from]);
        }
    }

    /**
     * Deletes an existing PageData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $slug
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($slug, $language)
    {
        $model = $this->findModel($slug, $language);
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the PageData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $slug
     * @param string $language
     * @return PageData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug, $language)
    {
        if (($model = PageData::findOne(['slug' => $slug, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
